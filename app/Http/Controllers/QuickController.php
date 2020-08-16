<?php


namespace App\Http\Controllers;


use App\Jobs\SendOrderQuickJob;
use App\Order;

class QuickController extends Controller
{
    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOrder(Order $order)
    {
        //SendOrderQuickJob::dispatch($order);

        return response()->json(['message' => 'Заказ отправлен в бегунок!']);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSendOrder(Order $order)
    {
        return response()->json(['is_send' => $order->is_send_quick]);
    }
}
