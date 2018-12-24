<?php

namespace App\Providers;

use App\Models\Realization;
use App\Observers\OrderObserver;
use App\Observers\RealizationObserver;
use App\Order;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       Schema::defaultStringLength(191);
       require_once(app_path() . '/Helpers/helpers.php');
       Order::observe(OrderObserver::class);
       Realization::observe(RealizationObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
