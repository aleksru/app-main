<?php

namespace App\Repositories;


use App\Models\Operator;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportRepository
{
    public function getAllOperatorsOrdersByDate($dateStart, $dateEnd = null)
    {
        if(!$dateStart){
            $dateStart = Carbon::today()->toDateTimeString();
        }

        if(!$dateEnd) {
            $dateEnd = Carbon::today()->addDay()->toDateTimeString();
        }

        $res = Operator::with([
            'orders' => function($query) use ($dateStart, $dateEnd){
                $query->where('created_at', '>=', $dateStart)->where('created_at', '<', $dateEnd);
            },
            'orders.realizations',

        ]);
        $orders = Order::with('realizations','realizations.product','realizations.supplier',
            'operator', 'client', 'deliveryPeriod', 'courier', 'metro', 'status');



        return $res;
    }
}