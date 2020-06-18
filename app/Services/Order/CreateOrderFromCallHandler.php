<?php


namespace App\Services\Order;


use App\Order;

class CreateOrderFromCallHandler extends CreateOrderHandler
{
    public function handle(): ?Order
    {
        $this->orderBuilder->setTypeCreateCall();
        $this->orderBuilder->addComment('Входящий звонок');
        return parent::handle();
    }
}