<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Artisan;

class OrderController extends Controller
{
    public function __construct(OrderService $orderService)
    {
        $this->service = $orderService;
    }


    public function index(Request $request){

        $orders = $this->service->getOrders($request->all());
        return new OrderResource($orders);
    }

    public function orderStatus(Request $request){

        $orderStatuses = $this->service->getOrderStatuses($request->all());
        return response()->json($orderStatuses, 200);
    }

    public function syncOrders(){

        Artisan::call('sync:woocommerce-orders');
        $response = Artisan::output();
        return response()->json(['message' => $response], 200);

    }
}
