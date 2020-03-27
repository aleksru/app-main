<?php

namespace App\Models;

use App\Order;
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
}
