<?php

namespace App;

use App\Enums\TypeCreatedOrder;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $guarded = ['id'];

    const IGNORE_STORES_NUMBERS = [
    ];

    protected $casts = [
        'is_hidden' => 'bool',
        'active_at' => 'datetime',
        'is_disable_api_price' => 'bool',
        'is_disable' => 'bool',
        'last_request_prices' => 'datetime',
    ];

    /**
     * Возвращает звонки в магазин
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(ClientCall::class);
    }

    /**
     * Заказы магазина
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Оставляем только цифры в номере телефона
     *
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Прайс магазина
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priceType()
    {
        return $this->belongsTo(PriceType::class);
    }

    public function getPriceProduct($productId)
    {
        return $this->priceType ? $this->priceType->getPrice($productId) : null;
    }

    /**
     * @param string $number
     * @return mixed
     */
    public static function getStoreByPhoneNumber(string $number)
    {
        return Store::where('phone', self::preparePhone($number))->first();
    }

    /**
     * @param string $url
     * @return mixed
     */
    public static function getStoreByUrl(string $url)
    {
        $url = str_replace(['https:', 'http:', '/', '\/'], '', $url);

        return Store::where('name', 'LIKE', "%{$url}%")->first();
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function preparePhone($value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);
        if(strlen($value) === 10){
            $value = '7' . $value;
        }

        return substr_replace($value, '7', 0, 1);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('is_hidden', 0);
    }

    /**
     * @return bool
     */
    public function isOnline() : bool
    {
        return $this->active_at > Carbon::now()->subMinutes(60);
    }

    /**
     * @param Carbon $time
     * @param string|null $type
     * @return int
     */
    public function getCountOrderAfterTime(Carbon $time, string $type = null): int
    {
        $query = $this->orders()
                    ->where('created_at', '>', $time->toDateTimeString());
        if($type){
            $query->where('type_created_order', $type);
        }

       return $query->count();
    }

    public function updateLastRequestPrices()
    {
        $this->last_request_prices = Carbon::now();
        $this->save();
    }

    public function defaultOrderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'default_order_status_id', 'id');
    }

    public function hasDefaultOrderStatus(): bool
    {
        return $this->default_order_status_id != null;
    }
}
