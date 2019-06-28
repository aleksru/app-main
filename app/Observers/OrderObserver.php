<?php

namespace App\Observers;

use App\Models\OrderStatus;
use App\Notifications\CreateOrder;
use App\Order;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        //
    }

    public function updating(Order $order)
    {
        if($order->getAttributeValue('status_id') != $order->getOriginal('status_id') &&
               ! $order->flag_send_sms) {
            $statusConfirm = OrderStatus::getIdStatusConfirm();
            if($statusConfirm && $order->status_id == $statusConfirm){
                $order->client->notify(new CreateOrder($order));
                $order->flag_send_sms = true;
            }
        }
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
