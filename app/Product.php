<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];
    
    const PRICE_LIST_ARTICUL = 'Артикул';
    const PRICE_LIST_PRODUCT = 'Товар';
    const PRICE_LIST_PRICE = 'Итоговая мин. цена';

    /**
     * пустой массив товаров
     * для заполнения в базу
     */
    const EMPTY_PRODUCTS = [['quantity' => '', 'articul' => '', 'name' => '', 'price' => '']];

    /**
     * Получение прайс-листа
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function priceList()
    {
      return $this->belongsToMany(PriceType::class);
    }
}
