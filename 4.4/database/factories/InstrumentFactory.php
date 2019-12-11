<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Instrument;
use Faker\Generator as Faker;

$factory->define(Instrument::class, function (Faker $faker) {
    $instruments = array(
        'guitar',
        'drums',
        'piano',
        'trumpet',
        'trombone',
        'cello',
        'violin',
    );

    $index = $faker->numberBetween(0, count($instruments) - 1);
    
    return [
        'name' => $instruments[$index],
        'price' => $faker->numberBetween(200, 5000),
    ];
});
