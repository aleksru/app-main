<?php

use Illuminate\Database\Seeder;

class ClientsInfoSuccessSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        $idConfirm = (new \App\Repositories\OrderStatusRepository)->getIdsStatusConfirmed();
        if( ! $idConfirm ){
            throw new Exception('Status Confirmed not found!');
        }
        \App\Order::query()->where('status_id', $idConfirm)->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                if(($client = $order->client) && $order->store_id){
                    $client->addSuccessStore($order->store_id);
                }
            }
        });
    }
}
