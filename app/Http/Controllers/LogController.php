<?php

namespace App\Http\Controllers;

use App\Order;


class LogController extends Controller
{
    public function order(Order $order)
    {
        dump($order->logs);
        dump($order->client->logs);
        dump($order->realizations()->withTrashed()->with('logs')->get());
//        foreach($order->realizations()->withTrashed()->get() as $log) {
//            dump($log->logs);
//        }

        return 1;
    }

}