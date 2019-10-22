<?php

namespace App\Providers;

use App\Client;
use App\Http\Composers\DeliveryWidget;
use App\Http\Composers\StateStoreWidget;
use App\Models\ClientPhone;
use App\Models\Realization;
use App\Observers\LogObserver;
use App\Observers\OrderObserver;
use App\Order;
use Illuminate\Pagination\Paginator;
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
       $this->registerObservers();
       $this->registerViewComposers();
        Paginator::defaultView('vendor.pagination.default');
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

    /**
     * Register observers
     */
    private function registerObservers()
    {
        Order::observe(LogObserver::class);
        Order::observe(OrderObserver::class);
        Realization::observe(LogObserver::class);
        Client::observe(LogObserver::class);
        ClientPhone::observe(LogObserver::class);
    }

    /**
     * Register view composers
     */
    private function registerViewComposers()
    {
        View::composer('front.widgets.delivery_periods_widget', DeliveryWidget::class);
        View::composer('front.widgets.state_stores_widget', StateStoreWidget::class);
    }
}
