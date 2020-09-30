<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{
    protected $guarded = ['id'];

    static public function getPriceTypesName():array
    {
        return self::pluck('name')->toArray();
    }

    public function products()
    {
      return $this->belongsToMany(Product::class)->withPivot(['price'])->withTimestamps();
    }

    public function getPrice($productId)
    {
        return $this->products()->where('product_id', $productId)->value('price');
    }
}
