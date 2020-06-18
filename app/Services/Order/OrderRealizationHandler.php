<?php


namespace App\Services\Order;

use App\Builders\OrderManager;
use App\Order;

class OrderRealizationHandler
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * OrderRealizationHandler constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function handle()
    {
        $orderManager = new OrderManager($this->order);
        $orderManager->addRealizationsFromProductsText();
    }
}