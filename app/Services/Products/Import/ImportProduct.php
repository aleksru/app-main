<?php


namespace App\Services\Products\Import;


use App\Product;
use App\Services\Products\Import\Data\ProductDTO;

class ImportProduct
{
    /**
     * @param ProductDTO $productData
     * @return Product
     */
    public function import(ProductDTO $productData): Product
    {
        /**
         * @var Product $product
         */
        $product = Product::withoutIsActive()->firstOrNew(['article' => $productData->getArticle()]);
        $product->product_name = $productData->getProductName();
        $product->is_active = 1;
        $product->save();

        return $product;
    }
}
