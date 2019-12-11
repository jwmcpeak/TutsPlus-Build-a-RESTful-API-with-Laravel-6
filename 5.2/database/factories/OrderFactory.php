<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'customer_id' => $faker->numberBetween(1, 50),
        'order_date' => $faker->dateTime(),
    ];
});
