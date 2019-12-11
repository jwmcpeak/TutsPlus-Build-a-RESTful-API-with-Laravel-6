<?php

namespace App\Services\v1;

use App\Instrument;

class InstrumentService extends ResourceService {
    protected $includes = ['order'];
    protected $queryFields = [
        'name' => 'last_name',
        'price' => 'last_name',
        'status' => 'email',
        'id' => 'id'
    ];

    protected $sortFields = [
        'name' => 'last_name',
        'price' => 'last_name',
        'status' => 'email',
        'id' => 'id'
    ];


    public function all($input) {
        

        $parms = $this->buildParameters($input);

        
        // thousands or 10s of thousands
        // return Order::cursor()->filter(function($order) use ($offset) {
        //     return $order->id >= $offset;
        // })->take($limit)->map([$this, 'formatToJson']);
        
        $query = Instrument::offset($parms['offset'])->
            limit($parms['limit']);

        if (!empty($parms['sort'])) {
            $query = $query->orderBy($parms['sort'][0], $parms['sort'][1]);
        }

        if (!empty($parms['include'])) {
            $query = $query->with($parms['include']);
        }

        if (!empty($parms['where'])) {
            $query = $query->where($parms['where']);
        }
        
        return $query->get()->map(function($order) use($parms) {
            return $this->formatToJson($order, $parms['include']);
        });
    }

    

    public function formatToJson($model, $includes = []) {
        $item = [
            'id' => $model->id,
            'name' => $model->name,
            'price' => $model->price,
            'status' => $model->status,
            'resourceUrl' => route('instruments.show', $model->id)
        ];

        if (in_array('order', $includes) && $model->order != NULL) {
            $item['order'] = [
                'id' => $model->order->id,
                'orderDate' => $model->order->order_date,
                'customer' => [
                    'id' => $model->order->customer_id,
                    'resourceUrl' => route('customers.show', $model->order->customer_id),

                ],
                'resourceUrl' => route('orders.show', $model->order->id),

            ];
        }

        return $item;
    }
}