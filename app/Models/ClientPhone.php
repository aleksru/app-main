<?php

namespace App\Models;

use App\Client;
use App\Log;
use Illuminate\Database\Eloquent\Model;

class ClientPhone extends Model
{
    protected $guarded = ['id'];
    protected $table = 'client_phones';

    /**
     * Клиент
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Оставяет только цифры в номере
     *
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Поиск по номеру телефона
     *
     * @param $query
     * @param $phone
     */
    public function scopeFindByPhone($query, $phone)
    {
        return $query->where('phone', preg_replace('/[^0-9]/', '', $phone));
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
