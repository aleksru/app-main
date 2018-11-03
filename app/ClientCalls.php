<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientCalls extends Model
{
    protected $guarded = ['id'];

    /**
     * Получение клиента
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
