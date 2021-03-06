<?php

namespace App\Providers;

use App\Client;
use App\Http\Composers\DeliveryWidget;
use App\Http\Composers\StateStoreWidget;
use App\MissedCall;
use App\Models\ClientPhone;
use App\Models\Realization;
use App\Observers\LogObserver;
use App\Observers\MissedCallObserver;
use App\Observers\OrderObserver;
use App\Observers\RealizationObserver;
use App\Order;
use App\Services\Docs\DomPdfService;
use App\Services\Docs\PdfServiceInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
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
        App::bind(PdfServiceInterface::class, DomPdfService::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPushOnce();
    }

    private function registerPushOnce()
    {
        Blade::directive('pushonce', function ($expression) {
            $stack = explode(',', $expression)[0];
            $name = explode(',', $expression)[1];
            return
                //check if identifier array exists
                "<?php if (!isset(\$__env->singlePush)) { \$__env->singlePush = []; } ?>" .
                //check if given stack exists
                "<?php if (!isset(\$__env->singlePush[{$stack}])) { \$__env->singlePush[{$stack}] = []; } ?>" .
                //start push to the stack
                "<?php if (!isset(\$__env->singlePush[{$stack}][{$name}])) : \$__env->singlePush[{$stack}][{$name}] = true; \$__env->startPush({$stack}); ?>";
        });
        Blade::directive('endpushonce', function () {
            //end push
            return "<?php \$__env->stopPush(); endif; ?>";
        });
    }

    /**
     * Register observers
     */
    private function registerObservers()
    {
        Order::observe(LogObserver::class);
        Order::observe(OrderObserver::class);
        Realization::observe(LogObserver::class);
        Realization::observe(RealizationObserver::class);
        Client::observe(LogObserver::class);
        ClientPhone::observe(LogObserver::class);
        MissedCall::observe(MissedCallObserver::class);
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
