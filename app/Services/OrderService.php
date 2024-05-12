<?php 

namespace App\Services;
use App\Models\Order;

class OrderService {

    public function __construct(Order $order) {
        $this->model = $order;
    }

    public function getOrders($data) {
        $query = $this->model->with('lineItems');

        if (isset($data['orderNumber'])) {
            $query->where('number', $data['orderNumber']);
        }

        if (isset($data['status'])) {
            $query->where('status', strtolower($data['status']));
        }

        if (isset($data['dateCreated'])) {
            $query->whereDate('date_created', $data['dateCreated']);
        }

        if (isset($data['sortField'])) {
            $query->orderBy($data['sortField'], $data['sortOrder'] ?? 'asc');
        } else {
            $query->orderBy('date_created', 'desc');
        }

        return $query->paginate(10);
    }

    public function getOrderStatuses() {
        $statuses = $this->model->distinct()->pluck('status')->map(function ($status) {
            return [
                strtolower($status) => ucfirst($status)
            ];
        })->collapse()->toArray();
        return $statuses;
        
    }
}