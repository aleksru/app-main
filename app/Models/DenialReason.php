<?php

namespace App\Models;

use App\Order;
use Illuminate\Database\Eloquent\Model;

class DenialReason extends Model
{
    protected $guarded = ['id'];

    /**
     * Заказы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
