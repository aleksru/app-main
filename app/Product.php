<?php

namespace App;

use App\Enums\ProductCategoryEnums;
use App\Enums\ProductType;
use App\Models\Realization;
use App\Models\Supplier;
use App\Models\Traits\ActiveTrait;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use ActiveTrait;

    protected $guarded = ['id'];

    protected $hidden = ['pivot'];

    const PRICE_LIST_ARTICUL = 'Артикул';
    const PRICE_LIST_PRODUCT = 'Товар';
    const PRICE_LIST_PRICE = 'Цена';
    const PRICE_LIST_PRICE_SPECIAL = 'Акция';

    /**
     * Префикс товаров созданных вручную
     */
    const PREFIX_CUSTOM_PRODUCT = '-custom-new';

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
        return $this->belongsToMany(PriceType::class)->withTimestamps();
    }

    /**
     * Реализация
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function realizations()
    {
        return $this->hasMany(Realization::class);
    }

    /**
     * Товар в заказах
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'realizations');
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

    /**
     * Строковый тип
     *
     * @return string
     */
    public function getTextType()
    {
        switch ($this->type) {
            case ProductType::TYPE_PRODUCT:
                return 'Товар';
            case ProductType::TYPE_ACCESSORY:
                return 'Аксессуар';
            case ProductType::TYPE_SERVICE:
                return 'Услуга';
            default:
                return '';
        }
    }

    public function getTextCategory()
    {
        return ProductCategoryEnums::getCategoriesDescription()[$this->category] ?? '';
    }

    public function isAirPods(): bool
    {
        return $this->type == ProductType::TYPE_PRODUCT && preg_match('/AirPods/', $this->product_name);
    }

    public function isMiBand(): bool
    {
        return $this->type == ProductType::TYPE_PRODUCT && preg_match('/Mi Band/', $this->product_name);
    }

    public function isDelivery(): bool
    {
        return $this->category == ProductCategoryEnums::DELIVERY;
    }

    /**
     * @param string $article
     * @return Product|null
     */
    public static function getFromArticle(string $article): ?self
    {
        return Product::byActicle($article)->first();
    }

    public function isFixPrice(): bool
    {
        return $this->fix_price !== null;
    }

    public static function createCustomArticle(): string
    {
        $article = Product::where('article', 'LIKE', '%'.Product::PREFIX_CUSTOM_PRODUCT.'%')->orderBy('id', 'desc')->first();
        $article = $article ? ((int)$article->article + 1).Product::PREFIX_CUSTOM_PRODUCT : '1000'.Product::PREFIX_CUSTOM_PRODUCT;

        return $article;
    }
}
