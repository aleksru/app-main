<?php

namespace App;

use App\Models\Courier;
use App\Models\DeliveryPeriod;
use App\Models\Operator;
use App\Models\OrderStatus;
use App\Models\Metro;
use App\Models\Realization;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use PivotEventTrait;

    protected $fillable = ['user_id', 'client_id','store_text','comment','status_id', 'courier_id',
                            'delivery_period_id','operator_id','date_delivery','products_text', 'metro_id', 'address',
                            'store_id', 'flag_denial_acc'
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
     * Продажи
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function realizations()
    {
        return $this->belongsToMany(Realization::class);
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

    /**
     * Фильтр по дате доставки
     * @param $query
     * @return mixed
     */
    public function scopeDeliveryToday($query, $date=null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        return $query->whereDate('date_delivery', $date);
    }

    /**
     * Фильтр по дате
     *
     * @param $query
     * @return mixed
     */
    public function scopeToDay($query, $date=null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        return $query->whereDate('created_at', $date);
    }

    /**
     * Получение id статуса Завершен
     *
     * @return integer|null
     */
    public static function statusFinallyId()
    {
         $statusID = DB::table('order_statuses')->where('status', 'LIKE', '%завершен%')->first();

         return $statusID ? $statusID->id : null;
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
