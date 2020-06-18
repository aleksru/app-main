<?php


namespace App\Services\Order;

use App\Order;

class CreateOrderFromApiHandler extends CreateOrderHandler
{
    public function handle(): ?Order
    {
        $this->orderBuilder->setTypeCreateApi();
        if( $order = parent::handle() ){
            $orderRealizationHandler = new OrderRealizationHandler($order);
            $orderRealizationHandler->handle();
        }

        return $order;
    }
}