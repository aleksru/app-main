<?php

namespace App\Http\Controllers;

use App\Order;


class LogController extends Controller
{
    /**
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function order(Order $order)
    {
        $fullLogsCollect = collect([]);

        $fullLogsCollect = $fullLogsCollect->merge($order->logs);
        $fullLogsCollect = $fullLogsCollect->merge($order->client->logs);

        foreach($order->realizations()->withTrashed()->has('logs')->get() as $item) {
            $fullLogsCollect = $fullLogsCollect->merge($item->logs);
        }

        $fullLogsCollect = $fullLogsCollect->sortByDesc(function($item, $key){
            return $item->created_at;
        });

        return view('front.logs.index', ['logs' => $fullLogsCollect]);
    }

}