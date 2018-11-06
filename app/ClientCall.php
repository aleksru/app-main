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
}
