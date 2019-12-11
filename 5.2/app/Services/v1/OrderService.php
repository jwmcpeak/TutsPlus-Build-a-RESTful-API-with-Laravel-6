<?php

namespace App\Services\v1;

use App\Order;

class OrderService extends ResourceService {
    protected $includes = ['customer', 'instruments'];
    protected $queryFields = [
        'orderdate' => 'order_date',
        'customer.id' => 'customer_id',
        'id' => 'id'
    ];

    protected $sortFields = [
        'orderdate' => 'order_date',
        'customer.id' => 'customer_id',
        'id' => 'id'
    ];


    public function all($input) {
        

        $parms = $this->buildParameters($input);

        
        // thousands or 10s of thousands
        // return Order::cursor()->filter(function($order) use ($offset) {
        //     return $order->id >= $offset;
        // })->take($limit)->map([$this, 'formatToJson']);
        
        $query = Order::offset($parms['offset'])->
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
        $customerId = $this->getCustomerId($payload['customer']);

        $order = Order::create([
            'customer_id' => $customerId,
            'order_date' => date("Y-m-d H:i:s")
        ]);

        return $this->formatToJson($order);
    }

    

    public function formatToJson($order, $includes = []) {
        $item = [
            'id' => $order->id,
            'customer' => [
                'id' => $order->customer_id,
                'resourceUrl' => route('customers.show', $order->customer_id)

            ],
            'orderDate' => $order->order_date,
            'resourceUrl' => route('orders.show', $order->id)
        ];

        if (in_array('customer', $includes)) {
            $item['customer'] = array_merge($item['customer'], [
                'fullName' => "{$order->customer->last_name}, {$order->customer->first_name}",
                'email' => $order->customer->email,
                'phone' => $order->customer->phone
            ]);
        }


        if (in_array('instruments', $includes)) {
            $item['instruments'] = $order->instruments->map(function($inst) {
                return [
                    'id' => $inst->id,
                    'name' => $inst->name,
                    'price' => $inst->price,
                    'resourceUrl' => route('instruments.show', $inst->id)

                ];
            });
        }


        return $item;
    }

    private function getCustomerId($info) {
        $customers = new CustomerService();

        if (isset($info['id'])) {
            $customer = $customers->fetch($info['id']);

            if ($customer == NULL) {
                throw new \Exception('Customer is not found');
            }

            return $customer->id;
        }

        return $customers->store($info)['id'];
    }
}