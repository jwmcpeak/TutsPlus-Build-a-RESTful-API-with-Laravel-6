<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Instrument::class, 100)->create();
        factory(App\Customer::class, 50)->create();
        factory(App\Order::class, 100)->create()->each(function($order) {
            factory(App\Instrument::class, rand(1, 15))->make()->
                each(function($instrument) use($order) {
                    $instrument->status = 'sold';
                    $order->instruments()->save($instrument);
            });
        });
    }
}
