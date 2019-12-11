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
    }
}
