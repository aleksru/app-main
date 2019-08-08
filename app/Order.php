<?php

namespace App;

use App\Models\City;
use App\Models\Courier;
use App\Models\DeliveryPeriod;
use App\Models\DeliveryType;
use App\Models\DenialReason;
use App\Models\Operator;
use App\Models\OrderStatus;
use App\Models\Metro;
use App\Models\OtherStatus;
use App\Models\Realization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = ['user_id', 'client_id','store_text','comment','status_id', 'courier_id',
                            'delivery_period_id','operator_id','date_delivery','products_text', 'metro_id', 'address',
                            'store_id', 'flag_denial_acc', 'order_id', 'communication_time', 'denial_reason_id', 'delivery_type_id', 'flag_send_sms',
                            'address_city', 'address_street', 'address_home', 'address_apartment', 'address_other', 'comment_logist', 'substatus_id',
                            'stock_status_id', 'logistic_status_id', 'city_id'
    ];
    
    protected $casts = [
        'products_text' => 'array',
        'flag_send_sms' => 'boolean'
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
     * Товары
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'realizations')->wherePivot('deleted_at', null);
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

    /**
     * Получение полного адреса
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $columns = ['address_city', 'address_street', 'address_home', 'address_apartment', 'address_other'];
        $address = '';

        foreach($columns as $column) {
            $address = $address . ($this->$column ? $this->$column . ', ' : '');
        }

        return $address;
    }

    /**
     * Подстатус заказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subStatus()
    {
        return $this->belongsTo(OtherStatus::class, 'substatus_id');
    }

    /**
     * Статус склада
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stockStatus()
    {
        return $this->belongsTo(OtherStatus::class);
    }

    /**
     * Статус логистика
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function logisticStatus()
    {
        return $this->belongsTo(OtherStatus::class);
    }

    /**
     * @return mixed
     */
    public function getFullSumAttribute()
    {
        return $this->realizations->sum('price');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @param $value
     * @return string
     */
    public function getDateDeliveryAttribute($value)
    {
        return $value ? Carbon::parse($value) : $value;
    }
}
