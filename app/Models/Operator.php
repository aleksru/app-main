<?php

namespace App\Models;

use App\Order;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $guarded = ['id'];

    /**
     * Заказы оператора
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
