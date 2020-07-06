<?php


namespace App\Repositories;


use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class OrderStatusRepository
{
    /**
     * @return mixed
     */
    public function getIdsStatusComplaining()
    {
        return Cache::remember('ID_ORDER_STATUS_COMPLAINT', Carbon::now()->addHours(4) ,function(){
            return OrderStatus::getIdsStatusComplaining();
        });
    }

    /**
     * @return mixed
     */
    public function getIdsStatusConfirmed()
    {
        return Cache::remember('ID_ORDER_STATUS_CONFIRM', Carbon::now()->addHours(4) ,function(){
            return OrderStatus::getIdStatusConfirm();
        });
    }

    public function getIdStatusMissedOutCall()
    {
        return Cache::remember('ID_ORDER_STATUS_MISSED_CALL', Carbon::now()->addHours(4), function (){
            return OrderStatus::getIdStatusForType(OrderStatus::STATUS_MISSED_PREFIX);
        });
    }

    public function getIdStatusNew()
    {
        return Cache::remember('ID_ORDER_STATUS_NEW', Carbon::now()->addHours(4) ,function(){
            return OrderStatus::getIdStatusNew();
        });
    }
}