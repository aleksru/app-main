<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $guarded = ['id'];

    /**
     * Префикс статуса нового заказа
     * @var string
     */
    const STATUS_NEW_PREFIX = 'новый';

    /**
     * Цвет по умолчанию
     *
     * @param $color
     * @return string
     */
    public function getColorAttribute($color)
    {
        if(! $color) {
            return 'default';
        }

        return $color;
    }

    /**
     * id Статусов для склада
     *
     * @return Collection
     */
    public static function getIdsStatusesForStock():Collection
    {
       return OrderStatus::where('status', 'like', '%' . StockUser::STATUS_PREFIX . '%' )->pluck('id');
    }

    /**
     * Получение ид статуса нового заказа
     *
     * @return string|null
     */
    public static function getIdStatusNew()
    {
       $status = OrderStatus::where('status', 'like', '%' . self::STATUS_NEW_PREFIX . '%' )->first();

       if(!$status) {
           return null;
       }

       return $status->id;
    }

    /**
     * id Статусов для логистика
     *
     * @return Collection
     */
    public static function getIdsStatusesForLogistic():Collection
    {
        return OrderStatus::where('status', 'like', '%' . Logist::STATUS_PREFIX . '%' )->pluck('id');
    }
}
