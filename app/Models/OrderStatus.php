<?php

namespace App\Models;

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
}
