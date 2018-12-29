<?php

namespace App\Repositories;


use App\Order;

class OrderRepository
{
    public function getOrdersToDateWithRelations($dateStart, $dateEnd=null)
    {
        $orders = Order::with('realizations','realizations.product','realizations.supplier',
                                'operator', 'client', 'deliveryPeriod', 'courier', 'metro', 'status');

        if(!$dateStart){
            $dateStart = date('Y-m-d');
        }

        if(!$dateEnd) {
           $orders->whereDate('created_at', $dateStart);
        }

        if($dateEnd) {
            $orders->whereDate('created_at', '>=', $dateStart)->whereDate('created_at', '<=', $dateEnd);
        }

        return $orders->get();

    }
}