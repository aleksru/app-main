<?php

namespace App\Providers;

use App\Client;
use App\Models\Logist;
use App\Models\StockUser;
use App\Order;
use App\Policies\ClientPolicy;
use App\Policies\LogisticPolicy;
use App\Policies\OrderPolicy;
use App\Policies\StockPolicy;
use App\Policies\UploadPricePolicy;
use App\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Product::class => UploadPricePolicy::class,
        Order::class => OrderPolicy::class,
        Client::class => ClientPolicy::class,
        StockUser::class => StockPolicy::class,
        Logist::class => LogisticPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
