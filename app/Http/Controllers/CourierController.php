<?php


namespace App\Http\Controllers;



use App\Models\Courier;
use App\Order;

class CourierController extends Controller
{
    public function get(Courier $courier = null)
    {
        if($courier){
            return response()->json($courier);
        }

        return response()->json(Courier::all());
    }

    public function forOrder(Order $order)
    {
        $sumOrder = $order->full_sum;
        $couriers = Courier::query()
                ->selectRaw('couriers.*')
                ->leftJoin('courier_statuses', 'couriers.courier_status_id', '=', 'courier_statuses.id')
                ->where('courier_statuses.max_sum_order', '>=', $sumOrder)
                ->orWhereNull('courier_statuses.max_sum_order')
                ->get();

        return response()->json($couriers);
    }
}