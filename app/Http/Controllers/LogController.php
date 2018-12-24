<?php

namespace App\Http\Controllers;

use App\Order;


class LogController extends Controller
{
    public function order(Order $order)
    {
        dump($order->logs);
        dump($order->client->logs);
        dump($order->realizations()->with('logs')->get());
        return 1;
    }

}