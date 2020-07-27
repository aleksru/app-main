<?php


namespace App\Services\Order;

use App\Enums\TypeCreatedOrder;
use App\Order;
use Carbon\Carbon;

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

    protected function checkClient()
    {
        parent::checkClient();
        if($this->store){
            $cnt = $this->store->getCountOrderAfterTime(
                Carbon::now()->subMinutes(config('order.api_block_minutes_create_store')),
                TypeCreatedOrder::API
            );

            if($cnt > config('order.api_block_create_count_store')){
                $this->orderBuilder->addComment('Система: Возможно СПАМ');
            }
        }
    }
}
