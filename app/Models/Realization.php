<?php

namespace App\Models;

use App\Log;
use App\Order;
use App\Product;
use App\Services\Quickrun\Orders\QuickSetOrderData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Realization extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $fillable = [
        "product_id", "price", "quantity", "imei", "price_opt", "supplier_id", "courier_payment", "delta"
    ];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'is_copy_logist' => 'boolean',
    ];

    /**
     * Продукт
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Поставщик
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
     * Заказ
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return QuickSetOrderData
     * @throws \Exception
     */
    public function prepareQuickData() : QuickSetOrderData
    {
        $data = new QuickSetOrderData();
        $order = $this->order->load('deliveryPeriod', 'client');
        if(!$order){
            throw new \Exception();
        }
        //$data->id             = $order->id . '_' .$this->id;
        $data->timeFrom       = $order->deliveryPeriod ? $order->deliveryPeriod->timeFrom . ':00' : null;
        $data->timeTo         = $order->deliveryPeriod ? $order->deliveryPeriod->timeTo . ':00' : null;
        $data->address        = $order->fullAddress;
        $data->goods          = $this->product ? $this->product->product_name : '';
        $data->buyerName      = $order->client ? $order->client->name : 'Не найдено';
        $data->number         = $order->id;
        $data->additionalInfo = $order->getAllProductsString() . " \n" . $order->comment;
        $data->price          = (int)$this->price;
        $data->phone          = $order->client ? $order->client->allPhones->implode(', ') : '';

        return $data;
    }
}
