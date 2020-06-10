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
}