<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class DeleteOldOrders extends Command
{
    protected $signature = 'orders:delete-old';
    protected $description = 'Delete orders not modified in the past 3 months';

    public function handle()
    {
        Order::where('updated_at', '<', now()->subMonths(3))->delete();

        $this->info('Old orders deleted successfully.');
    }
}