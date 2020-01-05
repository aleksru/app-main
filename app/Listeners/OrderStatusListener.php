<?php


namespace App\Listeners;

use App\Events\OrderUpdateRealizationsEvent;
use App\Events\UpdateRealizationsConfirmedOrderEvent;

class OrderStatusListener
{
    public function handle(OrderUpdateRealizationsEvent $event)
    {
        if($event->order->isConfirmed()){
            event(new UpdateRealizationsConfirmedOrderEvent($event->order));
        }
    }
}