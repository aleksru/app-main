<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $guarded = ['id'];

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
    public static function getIdsStatuesForStock()
    {
       return OrderStatus::where('status', 'like', '%' . StockUser::STATUS_PREFIX . '%' )->pluck('id');
    }
}
