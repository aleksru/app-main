<?php

use Illuminate\Database\Seeder;

class ClientsInfoComplaintsSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        $idComplaint = (new \App\Repositories\OrderStatusRepository)->getIdsStatusComplaining();
        if( ! $idComplaint ){
            throw new Exception('Status Complaint not found!');
        }
        \App\Order::query()->where('status_id', $idComplaint)->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                if(($client = $order->client) && $order->store_id){
                    $client->addComplaintStore($order->store_id);
                }
            }
        });
    }
}
