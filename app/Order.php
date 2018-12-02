<?php

namespace App;

use App\Models\Courier;
use App\Models\DeliveryPeriod;
use App\Models\Operator;
use App\Models\OrderStatus;
use App\Models\Metro;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'client_id','store_text','comment','status_id', 'courier_id',
                            'delivery_period_id','operator_id','date_delivery','products_text', 'metro_id', 'address',
                            'store_id'
    ];
    
    protected $casts = [
      'products_text' => 'array',
    ];

    /**
     * Получение клиента
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Статус заказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
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

    /**
     * Курьер
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    /**
     * Время доставки
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveryPeriod()
    {
        return $this->belongsTo(DeliveryPeriod::class);
    }

    /**
     * Оператор
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    /**
     * Товары
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('price', 'quantity', 'imei', 'price_opt', 'supplier_id', 'courier_payment', 'delta');
    }

    /**
     * Магазин заказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }


}
