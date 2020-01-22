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
use App\Models\Traits\HasSms;
use App\Services\Quickrun\Orders\QuickSetOrderData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasSms;

    protected $fillable = ['user_id', 'client_id','store_text','comment','status_id', 'courier_id',
                            'delivery_period_id','operator_id','date_delivery','products_text', 'metro_id', 'address',
                            'store_id', 'flag_denial_acc', 'order_id', 'communication_time', 'denial_reason_id', 'delivery_type_id', 'flag_send_sms',
                            'address_city', 'address_street', 'address_home', 'address_apartment', 'address_other', 'comment_logist', 'substatus_id',
                            'stock_status_id', 'logistic_status_id', 'city_id', 'utm_source', 'creator_user_id', 'full_address'
    ];
    
    protected $casts = [
        'products_text' => 'array',
        'flag_send_sms' => 'boolean',
        'communication_time' => 'datetime:d.m H:i',
        'created_at' => 'datetime:d.m.y H:i',
        'updated_at' => 'datetime:d.m.y H:i',
        'is_send_quick' => 'boolean',
        'date_delivery' => 'date'
    ];

    protected $dates = [
        'communication_time'
    ];
    public static function boot()
    {
        static::updating(function(Order $item) {
            $item->full_address = $item->prepareFullAddress();
        });
        parent::boot();
    }

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
    public function getFullAddressAttribute($value)
    {
        if (!$value) {
            return $this->prepareFullAddress();
        }

        return $value;
    }

    /**
     * @return string
     */
    private function prepareFullAddress()
    {
        $columns = [
            'address_street'  => '',
            'address_home' => 'д.',
            'address_apartment' => 'кв.',
            'address_other' => ''
        ];

        $city = $this->getCity();
        $address = ($city ? $city . ', ' : '') . ($this->metro ? 'м.' . $this->metro->name . ', ' : '');

        foreach($columns as $column => $mask) {
            $address = $address . ($this->$column ? ($mask . $this->$column . ', ') : '');
        }

        return $address;
    }

    public function getCity()
    {
        if($this->address_city){
            return $this->address_city;
        }
        if($this->city){
            return $this->city->name;
        }

        return null;
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
        return $this->realizations->reduce(function ($prev, $val){
            return $prev + ($val->quantity * $val->price);
        }, 0);
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

    /**
     * @param OrderStatus $orderStatus
     * @return int
     */
    public function getFullSumByStatus(OrderStatus $orderStatus)
    {
        if($this->status_id != $orderStatus->id){
            return 0;
        }
        return $this->realizations->reduce(function ($prev, $val){
            return $prev + ($val->quantity * $val->price);
        }, 0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function views()
    {
        return $this->belongsToMany(User::class, 'orders_views');
    }

    /**
     * @return bool
     */
    public function isConfirmed() : bool
    {
        return $this->status_id == OrderStatus::getIdStatusConfirm();
    }

    /**
     * @return string
     */
    public function getAllProductsString() : string
    {
        $res = $this->products->pluck('product_name')->implode(', ');

        return $res ? $res : '';
    }

    public function getAllProductsPriceString() : string
    {
        $this->load('realizations.product');
        $res = $this->realizations->reduce(function ($prev, $cur){
            return $prev . $cur->product->product_name . '-' . (int)$cur->price . 'р, ';
        }, '');

        return $res ? $res : '';
    }


    /**
     * @return QuickSetOrderData
     * @throws \Exception
     */
    public function prepareQuickData() : QuickSetOrderData
    {
        $data = new QuickSetOrderData();
        $order = $this->load('deliveryPeriod', 'client');
        if(!$order){
            throw new \Exception();
        }
        $fullSum = (int)$this->fullSum;
        //$data->id             = $order->id . '_' .$this->id;
        $data->timeFrom       = $order->deliveryPeriod ? $order->deliveryPeriod->timeFrom . ':00' : null;
        $data->timeTo         = $order->deliveryPeriod ? $order->deliveryPeriod->timeTo . ':00' : null;
        $data->address        = $order->full_address;
        $data->goods          = $order->getAllProductsPriceString();
        $data->buyerName      = $order->client ? $order->client->name : 'Не найдено';
        $data->number         = $order->id;
        $data->additionalInfo = $order->comment;
        $data->price          = $fullSum;
        $data->phone          = $order->client ? $order->client->allPhones->implode(', ') : '';
        $data->setWeight($fullSum);

        return $data;
    }
}
