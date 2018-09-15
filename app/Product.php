<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];
    
    const PRICE_LIST_ARTICUL = 'Артикул';
    const PRICE_LIST_PRODUCT = 'Товар';
    const PRICE_LIST_PRICE = 'Итоговая мин. цена';
    
    public function priceList()
    {
      return $this->belongsToMany(PriceType::class);
    }
}
