<?php

namespace App;

use App\Enums\ProductCategoryEnums;
use App\Enums\TextTypeEnums;
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
use App\Repositories\OrderStatusRepository;
use App\Services\Docs\Client\VoucherData;
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
                            'stock_status_id', 'logistic_status_id', 'city_id', 'utm_source', 'creator_user_id', 'full_address', 'confirmed_at', 'type_created_order', 'entry_id',
                            'comment_stock', 'courier_payment'
    ];

    protected $casts = [
        'products_text' => 'array',
        'flag_send_sms' => 'boolean',
        'communication_time' => 'datetime:d.m H:i',
        'created_at' => 'datetime:d.m.y H:i',
        'updated_at' => 'datetime:d.m.y H:i',
        'is_send_quick' => 'boolean',
        'date_delivery' => 'date',
        'confirmed_at' => 'datetime'
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
     * @param string $type
     * @return int
     */
    public function getSumProductForType(string $type) : int
    {
        return $this->realizations
            ->reduce(function ($prev, $val) use ( $type ){
                return $prev + ($val->product_type == $type ? ($val->quantity * $val->price) : 0);
            }, 0);
    }

    public function getCountProductForType(string $type) : int
    {
        return $this->realizations
            ->reduce(function ($prev, $val) use ( $type ){
                return $prev + ($val->product_type == $type ? 1 : 0);
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
     * @return bool
     */
    public function isNew() : bool
    {
        return $this->status_id == (new OrderStatusRepository)->getIdStatusNew();
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function call()
    {
        return $this->hasOne(ClientCall::class, 'entry_id', 'entry_id');
    }

    public function getStringCustomerInfo()
    {
        $data = [
            $this->client->name,
            'Телефон' => $this->client->all_phones->implode(', '),
            'Адрес' => $this->full_address,
            'Время' => $this->deliveryPeriod ? $this->deliveryPeriod->period_text : null
        ];
        $data = array_filter($data);
        $result = '';
        foreach ($data as $key => $value){
            if($key){
                $result .= $key . ': ' . $value . '. ';
            }else{
                $result .= $value . '. ';
            }

        }

        return $result;
    }

    public function isWithDelivery() : bool
    {
        return $this->products->contains(function (Product $value) {
            return $value->isDelivery();
        });
    }

    public function voucherDataFactory() : VoucherData
    {
        $voucher = new VoucherData();
        $voucher->setNumberOrder($this->id);
        $voucher->setDateDelivery($this->date_delivery);
        $voucher->setRealizations($this->realizations()->withoutRefusal()->get()->reject(function ($value, $key) {
            return $value->product->isDelivery();
        })->all());
        $voucher->setClientInfo($this->getStringCustomerInfo());
        $voucher->setCorporateInfo(get_string_corp_info());
        $voucher->setWarrantyText(setting(TextTypeEnums::VOUCHER_FULL) ?? '');

        return $voucher;
    }

    public function voucherDeliveryDataFactory() : VoucherData
    {
        $voucher = new VoucherData();
        $voucher->setNumberOrder($this->id);
        $voucher->setDateDelivery($this->date_delivery);
        $voucher->setRealizations($this->realizations()->withoutRefusal()->get()->filter(function ($value, $key) {
            return $value->product->isDelivery();
        })->all());
        $voucher->setClientInfo($this->getStringCustomerInfo());
        $voucher->setCorporateInfo(get_string_delivery_corp_info());
        $voucher->setWarrantyText(setting(TextTypeEnums::DELIVERY_FULL) ?? '');

        return $voucher;
    }

    /**
     * @return OrderBuilder
     */
    public static function getBuilder(): OrderBuilder
    {
        return new OrderBuilder();
    }

    /**
     * @return int
     */
    public function getCountRealizations(): int
    {
        return $this->realizations()->count();
    }

    /**
     * @return array OrderProductTextData
     */
    public function getProductTextDataArray(): array
    {
        $result = [];
        if( is_array($this->products_text) ){
            foreach ($this->products_text as $item){
                $result[] = new OrderProductTextData(
                    $item['name'],
                    $item['articul'],
                    (int)($item['quantity'] ?? 1),
                    (float)($item['price'] ?? 0)
                );
            }
        }

        return $result;
    }

    public function isAllowedSetCourier(Courier $courier): bool
    {
        if($courier->isUnLimit()){
            return true;
        }

        return $this->full_sum <= $courier->getMaxAllowedOrderSummary();
    }
}
