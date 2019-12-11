<?php

namespace App\Services\v1;

use App\Order;

class OrderService {
    public function all($limit, $offset) {
        if ($limit > 50) {
            $limit = 50;
        }
        
        // thousands or 10s of thousands
        // return Order::cursor()->filter(function($order) use ($offset) {
        //     return $order->id >= $offset;
        // })->take($limit)->map([$this, 'formatToJson']);

        return Order::offset($offset)->
            limit($limit)->get()->map([$this, 'formatToJson']);
    }

    public function formatToJson($order) {
        return [
            'id' => $order->id,
            'customerId' => $order->customer_id,
            'orderDate' => $order->order_date
        ];
    }
}