<?php

namespace App\Providers;

use App\Events\OrderUpdateRealizationsEvent;
use App\Listeners\Login;
use App\Listeners\LogoutListener;
use App\Listeners\OrderStatusListener;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SendSmsClient' => [
            'App\Listeners\SaveSmsLog',
        ],

        LoginEvent::class => [
            Login::class
        ],

        Logout::class => [
            LogoutListener::class
        ],

        OrderUpdateRealizationsEvent::class => [
            OrderStatusListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
