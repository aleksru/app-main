<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = ['id'];

    /**
     * Получаение звонков клиента
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(ClientCall::class);
    }

    /**
     * Получение заказов клиента
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Получение клиента по номеру телефона
     * @param $query
     * @param $phone
     */
    public function scopeGetOnPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }
}
