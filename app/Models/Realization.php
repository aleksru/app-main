<?php

namespace App\Models;

use App\Log;
use App\Order;
use App\Product;
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
}
