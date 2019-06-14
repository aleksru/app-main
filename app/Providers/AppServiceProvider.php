<?php

namespace App\Providers;

use App\Client;
use App\Http\Composers\DeliveryWidget;
use App\Models\ClientPhone;
use App\Models\Realization;
use App\Observers\LogObserver;
use App\Order;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

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

       Order::observe(LogObserver::class);
       Realization::observe(LogObserver::class);
       Client::observe(LogObserver::class);
       ClientPhone::observe(LogObserver::class);
       View::composer('front.widgets.delivery_periods_widget', DeliveryWidget::class);
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
