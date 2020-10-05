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
      return $this->belongsToMany(Product::class)->withPivot(['price', 'price_special'])->withTimestamps();
    }

    public function getPrice($productId)
    {
        if($product = $this->products()->where('product_id', $productId)->first()){
            return $product->pivot->price_special !== null ? $product->pivot->price_special : $product->pivot->price;
        }

        return null;
    }

    public function clearPrices()
    {
        $this->products()->detach();
    }
}
