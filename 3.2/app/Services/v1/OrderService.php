<?php

namespace App\Services\v1;

use App\Order;

class OrderService {
    public function all() {
        return Order::all()->map([$this, 'formatToJson']);
    }

    public function formatToJson($order) {
        return [
            'id' => $order->id,
            'customerId' => $order->customer_id,
            'orderDate' => $order->order_date
        ];
    }
}