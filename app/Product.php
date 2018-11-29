<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];
    
    const PRICE_LIST_ARTICUL = 'Артикул';
    const PRICE_LIST_PRODUCT = 'Товар';
    const PRICE_LIST_PRICE = 'Цена';

    /**
     * Префикс товаров созданных вручную
     */
    const PREFIX_CUSTOM_PRODUCT = '-custom';

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

    /**
     * Заказы
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    /**
     * Поиск по артикулу
     *
     * @param $query
     * @param $article
     * @return mixed
     */
    public function scopeByActicle($query, $article)
    {
        return $query->where('article', $article);
    }
}
