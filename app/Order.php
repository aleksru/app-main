<?php

namespace App;

use App\Models\Courier;
use App\Models\DeliveryPeriod;
use App\Models\DeliveryType;
use App\Models\DenialReason;
use App\Models\Operator;
use App\Models\OrderStatus;
use App\Models\Metro;
use App\Models\Realization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = ['user_id', 'client_id','store_text','comment','status_id', 'courier_id',
                            'delivery_period_id','operator_id','date_delivery','products_text', 'metro_id', 'address',
                            'store_id', 'flag_denial_acc', 'order_id', 'communication_time', 'denial_reason_id', 'delivery_type_id'
    ];
    
    protected $casts = [
      'products_text' => 'array',
    ];

    protected $dates = [
        'communication_time'
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
     * Реализации
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function realizations()
    {
        return $this->hasMany(Realization::class);
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
     * @param null $date
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
     * Фильтр по интервалу дат
     *
     * @param $query
     * @param $date
     * @param $dateEnd
     * @return mixed
     */
    public function scopeDateInterval($query, $date, $dateEnd)
    {
        return $query->whereDate('created_at', '>=', $date)->whereDate('created_at', '<=', $dateEnd);
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

    /**
     * @param $value
     */
    public function setCommunicationTimeAttribute($value)
    {
        $value = $value ? Carbon::createFromFormat('Y-m-d\TH:i', $value) : null;
        $this->attributes['communication_time'] = $value;
    }

    /**
     * Причина отказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function denialReason()
    {
        return $this->belongsTo(DenialReason::class);
    }

    /**
     * Тип доставки
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveryType()
    {
        return $this->belongsTo(DeliveryType::class);
    }


}
