<?php


namespace App\Http\Controllers;



use App\Models\Courier;
use App\Order;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function get(Courier $courier = null)
    {
        if($courier){
            return response()->json($courier);
        }

        return response()->json(Courier::all());
    }

    public function forOrder(Order $order, Request $request)
    {
        $couriers = Courier::bySummary($order->full_sum);
        if($query = $request->get('term')){
            $couriers->where('couriers.name', 'like', "%{$query}%");
        }

        return response()->json($couriers->get());
    }
}
