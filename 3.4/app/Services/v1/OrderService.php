<?php

namespace App\Services\v1;

use App\Order;

class OrderService {
    public function all($limit, $offset, $sort) {
        if ($limit > 50) {
            $limit = 50;
        }

        $sortInfo = $this->buildSort($sort);
        
        // thousands or 10s of thousands
        // return Order::cursor()->filter(function($order) use ($offset) {
        //     return $order->id >= $offset;
        // })->take($limit)->map([$this, 'formatToJson']);

        return Order::orderBy($sortInfo[0], $sortInfo[1])->offset($offset)->
            limit($limit)->get()->map([$this, 'formatToJson']);
    }

    private function buildSort($rawSort) {
        if (empty($rawSort)) {
            return ['order_date', 'asc'];
        }

        $orderable = collect([
            'orderdate' => 'order_date',
            'customerid' => 'customer_id',
            'id' => 'id'
        ]);

        $direction = collect([
            'asc' => 'asc',
            'desc' => 'desc'
        ]);

        $parts = explode(':', strtolower($rawSort));

        $field = $orderable->get($parts[0]) ?? 'order_date';
        $dir = $direction->get($parts[1] ?? '') ?? 'asc';

        return [$field, $dir];

    }

    public function formatToJson($order) {
        return [
            'id' => $order->id,
            'customerId' => $order->customer_id,
            'orderDate' => $order->order_date
        ];
    }
}