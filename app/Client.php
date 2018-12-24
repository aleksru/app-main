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
        return $query->where('phone', preg_replace('/[^0-9]/', '', $phone));
    }

    /**
     * Оставляем только цифры в номере телефона
     *
     * @param  string  $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Логи
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logs()
    {
        return $this->morphMany(Log::class, 'logtable');
    }
}
