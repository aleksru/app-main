<?php

namespace App;

use App\Models\Metro;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
      'products' => 'array',
    ];

    /**
     * Получение клиента
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Станция метро доставки заказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metro()
    {
        return $this->belongsTo(Metro::class);
    }
}
