<?php

namespace App\Models;

use App\Order;
use App\Services\Docs\Courier\RouteListData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'birth_day' => 'date',
        'passport_date' => 'date',
    ];

    /**
     * Заказы на доставку
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function status()
    {
        return $this->belongsTo(CourierStatus::class, 'courier_status_id', 'id');
    }

    public function getRouteListDataFactory(Carbon $date) : RouteListData
    {
        $orders = $this->orders()
            ->with(['realizations' => function($query){
                $query->withoutRefusal();
                }, 'client', 'realizations.product', 'deliveryPeriod'])
            ->whereDate('date_delivery', $date)
            ->get()
            ->sortBy(function ($order, $key) {
                return $order->deliveryPeriod ? $order->deliveryPeriod->timeFrom : null;
            });
        $routeListData = new RouteListData();
        $routeListData->setCourier($this->name);
        $routeListData->setCorporateInfo(get_string_corp_info());
        $routeListData->setDateDelivery($date);
        $routeListData->setOrders($orders->all());

        return $routeListData;
    }
}
