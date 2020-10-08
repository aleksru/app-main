<?php


namespace App\Services\Products\Import;


use App\Services\Products\Import\Data\ProductDTO;
use App\Services\Products\Prices\Import\PriceDTO;

interface ImportProductPriceImp
{
    function import(ProductDTO $productDTO, PriceDTO $priceDTO);
}
