<?php

namespace App\Models;

use App\Log;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Realization extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        "product_id", "price", "quantity", "imei", "price_opt", "supplier_id", "courier_payment", "delta"
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
}
