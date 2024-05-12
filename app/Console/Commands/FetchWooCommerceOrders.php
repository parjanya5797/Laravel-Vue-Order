<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchWooCommerceOrders extends Command
{
    protected $signature = 'sync:woocommerce-orders';
    protected $description = 'Fetch and sync orders from WooCommerce API';

    public function handle()
    {
        
        try {
            $response = Http::withBasicAuth(env('WOOCOMMERCE_CONSUMER_KEY'), env('WOOCOMMERCE_CONSUMER_SECRET'))
                        ->get(env('WOOCOMMERCE_API_URL') . '/orders', [
                            'after' => now()->subDays(30)->format('Y-m-d\TH:i:s'),
                        ]);

            if ($response->successful()) {
                $orders = $response->json();
                foreach ($orders as $orderData) {
                    $this->syncOrder($orderData);
                }
                $this->info('Orders synced successfully.');
            } else {
                $this->error('Failed to fetch orders from WooCommerce API. Status code: ' . $response->status());
                Log::error('Failed to fetch orders with status: ' . $response->status(), ['response' => $response->body()]);
                event(new \App\Events\OrderFetchFailed($response->status(), $response->body()));
            }
        } catch (\Throwable $th) {
            $this->error("Something went wrong.");
            Log::error('Error fetching orders: ' . $th->getMessage(), ['exception' => $th]);
            event(new \App\Events\OrderFetchFailed($th->getCode(), $th->getMessage()));
        }
    }

    private function syncOrder($orderData)
    {
        DB::transaction(function () use ($orderData) {
            $order = Order::updateOrCreate(['order_key' => $orderData['order_key']], [
                'number' => $orderData['number'],
                'order_key' => $orderData['order_key'],
                'status' => $orderData['status'],
                'date_created' => $orderData['date_created'],
                'total' => $orderData['total'],
                'customer_id' => $orderData['customer_id'],
                'customer_note' => $orderData['customer_note'],
                'billing' => json_encode($orderData['billing']),
                'shipping' => json_encode($orderData['shipping']),
            ]);

            foreach ($orderData['line_items'] as $item) {
                $order->lineItems()->updateOrCreate([
                    'order_id' => $order['id'],
                    'product_id' => $item['product_id']
                ], 
                [
                    'name' => $item['name'],
                    'variation_id' => $item['variation_id'],
                    'quantity' => $item['quantity'],
                    'tax_class' => $item['tax_class'],
                    'subtotal' => $item['subtotal'],
                    'subtotal_tax' => $item['subtotal_tax'],
                    'total' => $item['total'],
                    'total_tax' => $item['total_tax'],
                    'sku' => $item['sku'],
                    'price' => $item['price'],
                    'image_src' => json_encode($item['image']),
                    'parent_name' => $item['parent_name']
                ]);
            }
        });
    }
}
