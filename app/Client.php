<?php

namespace App;

use App\Models\ClientPhone;
use App\Models\Traits\HasSms;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use Notifiable, HasSms;

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
     * @param  string $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        if (strlen($value) === 10) {
            $value = '7' . $value;
        }
        $this->attributes['phone'] = substr_replace($value, '7', 0, 1);
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function preparePhone($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        if (strlen($value) === 10) {
            $value = '7' . $value;
        }

        return substr_replace($value, '7', 0, 1);
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

        $this->additionalPhones->each(function ($item, $key) use (&$phones) {
            $phones = $phones . ' ' . $item->phone;
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
        $phone = self::preparePhone($phone);
        $client = Client::getOnPhone($phone)->first();

        if (!$client) {
            $client = ClientPhone::findByPhone($phone)->first();
            $client ? $client = $client->client : null;
        }

        return $client;
    }

    /**
     * @param int $statusId
     * @return int
     */
    public function getOrdersCountForStatus(int $statusId) : int
    {
        return $this->orders()->where('status_id', $statusId)->count();
    }
}
