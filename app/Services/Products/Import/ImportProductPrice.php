<?php


namespace App\Services\Products\Import;

use App\PriceType;
use App\Services\Products\Prices\Import\ImportPrice;
use App\Services\Products\Import\Data\ProductDTO;
use App\Services\Products\Prices\Import\PriceDTO;

class ImportProductPrice implements ImportProductPriceImp
{
    /**
     * @var ImportProduct
     */
   protected $importProduct;

    /**
     * @var ImportPrice
     */
   protected $importPrice;

    /**
     * @var PriceType
     */
   protected $priceList;

    /**
     * ImportProductPrice constructor.
     * @param $priceList
     */
    public function __construct(PriceType $priceList)
    {
        $this->priceList = $priceList;
        $this->importProduct = new ImportProduct();
        $this->importPrice = new ImportPrice();
    }

    /**
     * @param ProductDTO $productDTO
     * @param PriceDTO $priceDTO
     * @throws \InvalidArgumentException
     */
   public function import(ProductDTO $productDTO, PriceDTO $priceDTO)
   {
       if($productDTO->getArticle() !== $priceDTO->getProductArticle()){
           throw new \InvalidArgumentException('Articles do not match!');
       }
       $product = $this->importProduct->import($productDTO);
       $this->importPrice->import($priceDTO, $product, $this->priceList);
   }
}
