<?php


namespace App\Services\Products\Prices\Import;


use App\PriceType;
use App\Product;

class ImportPrice
{
    /**
     * @param PriceDTO $priceData
     * @param Product $product
     * @param PriceType $priceType
     */
    public function import(PriceDTO $priceData, Product $product, PriceType $priceType)
    {
        $product->priceList()->attach($priceType->id, [
            'price'         => $priceData->getPrice(),
            'price_special' => $priceData->getPriceSpecial(),
        ]);
    }
}
