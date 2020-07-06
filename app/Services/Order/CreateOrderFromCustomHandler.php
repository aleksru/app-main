<?php


namespace App\Services\Order;


use App\Order;

class CreateOrderFromCustomHandler extends CreateOrderHandler
{
    protected function setStatus()
    {
    }

    public function handle(): ?Order
    {
        $this->orderBuilder->setTypeCreateCustom();
        return parent::handle();
    }
}