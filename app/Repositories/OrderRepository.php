<?php

namespace App\Repositories;


use App\ClientCall;
use App\Models\OrderStatus;
use App\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFreeOperatorOrders(Carbon $date) : Collection
    {
        $statusNew = Cache::remember('ID_ORDER_STATUS_NEW', Carbon::now()->addHours(4), function (){
            return OrderStatus::getIdStatusNew();
        });
        if($statusNew){
            return self::getOrderQueryByRelations()->where('status_id', $statusNew)
                        ->whereDate('created_at', $date)
                        ->whereNull('operator_id')
                        ->orderBy('created_at', 'desc')
                        ->get();
        }

        return Order::whereNull('id')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCallBacksOrders() : Collection
    {
        $orderStatusCallBack = Cache::remember('ID_ORDER_STATUS_CALL_BACK', Carbon::now()->addHours(4), function (){
            return OrderStatus::getIdStatusForType(OrderStatus::STATUS_CALLBACK_PREFIX);
        });

        if($orderStatusCallBack) {
                return self::getOrderQueryByRelations()->whereBetween('communication_time', [
                    Carbon::now()->subMinutes(10),
                    Carbon::now()->addMinutes(50)
                ])->orderBy('communication_time')->get();
        }

        return Order::whereNull('id')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrdersForRecall() : Collection
    {
//        select orders.id, COUNT(client_calls.id) as cnt from `orders`
//        JOIN client_calls ON orders.client_id = client_calls.client_id AND orders.created_at >= client_calls.created_at
//        where `status_id` = 15 AND client_calls.type = 'OUTGOING'
//        GROUP BY orders.id
//        HAVING cnt <= 3
        $ids = [];

        $orderStatusMissed = Cache::remember('ID_ORDER_STATUS_MISSED_CALL', Carbon::now()->addHours(4), function (){
            return OrderStatus::getIdStatusForType(OrderStatus::STATUS_MISSED_PREFIX);
        });

        if($orderStatusMissed){
           $ids = DB::table('orders')
                ->selectRaw('orders.id, COUNT(client_calls.id) as cnt')
                ->join('client_calls', function ($join) {
                    $join->on('orders.client_id', '=', 'client_calls.client_id')
                        ->on('orders.created_at', '>=', 'client_calls.created_at');
                })->where('orders.status_id', $orderStatusMissed)
                ->where('client_calls.type', ClientCall::outgoingCall)
                ->whereDate('orders.created_at', '>=', Carbon::today()->subDays(2))
                ->groupBy('orders.id')
                ->having('cnt', '<=', 3)
                ->pluck('orders.id');
        }

        return self::getOrderQueryByRelations()->whereIn('id', $ids)
                                            ->orderBy('updated_at')
                                            ->get();
    }

    private static function getOrderQueryByRelations() : Builder
    {
        return Order::query()->with('status', 'client', 'views', 'store');
    }
}