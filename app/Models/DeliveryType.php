<?php

namespace App\Models;

use App\Order;
use Illuminate\Database\Eloquent\Model;

class DeliveryType extends Model
{
    protected $guarded = ['id'];

    /**
     * Заказ
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
