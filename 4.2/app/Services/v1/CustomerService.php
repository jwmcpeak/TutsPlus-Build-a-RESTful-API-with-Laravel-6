<?php

namespace App\Services\v1;

use App\Customer;

class CustomerService extends ResourceService {
    protected $includes = ['orders'];
    protected $queryFields = [
        'lastname' => 'last_name',
        'firstname' => 'last_name',
        'email' => 'email',
        'id' => 'id'
    ];

    protected $sortFields = [
        'lastname' => 'last_name',
        'firstname' => 'last_name',
        'email' => 'email',
        'id' => 'id'
    ];


    public function all($input) {
        

        $parms = $this->buildParameters($input);

        
        // thousands or 10s of thousands
        // return Order::cursor()->filter(function($order) use ($offset) {
        //     return $order->id >= $offset;
        // })->take($limit)->map([$this, 'formatToJson']);
        
        $query = Customer::offset($parms['offset'])->
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

    public function store($payload) {
        $customer = Customer::create([
            'first_name' => $payload['firstName'],
            'last_name' => $payload['lastName'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
        ]);

        return $this->formatToJson($customer);
    }

    public function fetch($id) {
        return Customer::find($id);
    }

    

    public function formatToJson($model, $includes = []) {
        $item = [
            'id' => $model->id,
            'lastName' => $model->last_name,
            'firstName' => $model->first_name,
            'email' => $model->email,
            'phone' => $model->phone,
            'resourceUrl' => route('customers.show', $model->id)
        ];

        if (in_array('orders', $includes)) {
            $item['orders'] = $model->orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'orderDate' => $order->order_date,
                    'resourceUrl' => route('orders.show', $order->id)

                ];
            });
        }


        return $item;
    }
}