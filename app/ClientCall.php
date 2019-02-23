<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientCall extends Model
{
    protected $guarded = ['id'];

    /**
     * константа
     * тип звонка
     * входящий
     */
    const incomingCall = 'INCOMING';

    /**
     * константа
     * тип звонка
     * исходящий
     */
    const outgoingCall = 'OUTGOING';

    /**
     * Получение клиента
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Возвращает мазагин
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Получаем тип звонка
     * @return string
     */

    public function getTypeCallAttribute()
    {
        if ($this->type === self::incomingCall){
            return 'Входящий';
        }
        if ($this->type === self::outgoingCall) {
            return 'Исходящий';
        }

        return 'Не определен';
    }

    /**
     * Получение последнего звонка для номера
     *
     * @param $number
     * @return Model|null
     */
    public static function getLastCallForNumber($number)
    {
        return ClientCall::where('from_number', $number)->orderBy('created_at', 'desc')->first();
    }

    /**
     * Получение имени клиента
     *
     * @return string|null
     */
    public function getClientNameAttribute()
    {
        return $this->client ? $this->client->name : null;
    }

    /**
     * Получение названия магазина
     *
     * @return string|null
     */
    public function getStoreNameAttribute()
    {
        return $this->store ? $this->store->name : null;
    }

}
