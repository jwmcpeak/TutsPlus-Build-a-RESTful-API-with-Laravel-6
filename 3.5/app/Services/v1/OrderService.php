<?php

namespace App\Services\v1;

use App\Order;

class OrderService {
    public function all($limit, $offset, $sort, $where) {
        if ($limit > 50) {
            $limit = 50;
        }

        $sortInfo = $this->buildSort($sort);
        $whereClauses = $this->buildWhere($where);

        
        // thousands or 10s of thousands
        // return Order::cursor()->filter(function($order) use ($offset) {
        //     return $order->id >= $offset;
        // })->take($limit)->map([$this, 'formatToJson']);
        
        $query = Order::orderBy($sortInfo[0], $sortInfo[1])->
            offset($offset)->
            limit($limit);

        if (!empty($whereClauses)) {
            $query = $query->where($whereClauses);
        }
        
        return $query->get()->map([$this, 'formatToJson']);
    }

    private function buildWhere($rawWhere) {
        if (empty($rawWhere)) {
            return [];
        }

        $queryable = collect([
            'orderdate' => 'order_date',
            'customerid' => 'customer_id',
            'id' => 'id'
        ]);

        $operators = collect([
            'eq' => '=',
            'ne' => '<>',
            'lt' => '<',
            'lte' => '<=',
            'gt' => '>',
            'gte' => '>=',
        ]);

        //column:operator:value,column2:operator2:value2
        $rawClause = collect(explode(',', strtolower($rawWhere)));
        $clauses = collect([]);

        $rawClause->each(function($item, $key) use($queryable, $operators, $clauses) {
            //column:operator:value
            $parts = explode(':', $item);

            if (count($parts) != 3) {
                return false;
            }

            $field = $queryable->get($parts[0]) ?? '';
            $operator = $operators->get($parts[1]) ?? '';

            if (empty($field) || empty($operator)) {
                return false;
            }

            $clauses->push([
                $field,
                $operator,
                $parts[2]
            ]);

        });

        return $clauses->all();
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