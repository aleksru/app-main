<?php

namespace App;

use App\Models\ClientPhone;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * Доп номера телефонов
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function additionalPhones()
    {
        return $this->hasMany(ClientPhone::class);
    }

    /**
     * Получить все номера клиента
     *
     * @return Collection
     */
    public function getAllPhonesAttribute()
    {
        return $this->additionalPhones->pluck('phone')->push($this->phone);
    }

    /**
     * Получить все доп номера клиента
     *
     * @return Collection
     */
    public function getAllAdditionalPhonesAttribute()
    {
        $phones = '';

        $this->additionalPhones->each(function ($item, $key) use (&$phones){
            $phones = $phones.' '.$item->phone;
        });

        return $phones;
    }

    /**
     * Получение клиента по номеру телефона
     *
     * @param string $phone
     * @return Model|null
     */
    public static function getClientByPhone(string $phone)
    {
        $client = Client::getOnPhone($phone)->first();

        if(!$client) {
            $client = ClientPhone::findByPhone($phone)->first();
            $client ? $client = $client->client : null;
        }

        return $client;
    }
}
