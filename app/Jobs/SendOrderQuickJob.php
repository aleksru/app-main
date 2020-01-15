<?php

namespace App\Jobs;

use App\Order;
use App\Services\Quickrun\Orders\QuickrunOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOrderQuickJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    private $order;

    /**
     * SendOrderQuickJob constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->queue = 'quick';
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $c = (new QuickrunOrder ());
        $c->addOrder($this->order->prepareQuickData());
        $c->setOrders($this->order->date_delivery);
        $this->order->is_send_quick = true;
        $this->order->save();
    }
}
