<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services;


class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        v1\OrderService::class => v1\OrderService::class,
        v1\CustomerService::class => v1\CustomerService::class,
    ];


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
