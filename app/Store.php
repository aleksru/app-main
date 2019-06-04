<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $guarded = ['id'];

    /**
     * Возвращает звонки в магазин
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(ClientCall::class);
    }

    /**
     * Заказы магазина
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Оставляем только цифры в номере телефона
     *
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Прайс магазина
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priceType()
    {
        return $this->belongsTo(PriceType::class);
    }

    /**
     * @param string $number
     * @return mixed
     */
    public static function getStoreByPhoneNumber(string $number)
    {
        return Store::where('phone', self::preparePhone($number))->first();
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function preparePhone($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        if(strlen($value) === 10){
            $value = '7' . $value;
        }

        return substr_replace($value, '7', 0, 1);
    }
}
