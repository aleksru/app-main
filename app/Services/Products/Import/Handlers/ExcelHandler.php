<?php


namespace App\Services\Products\Import\Handlers;


use App\Services\Products\Import\Excel\ProductImport;
use App\Services\Products\Import\ImportHandlerImp;
use App\Services\Products\Import\ImportProductPriceImp;
use Maatwebsite\Excel\Facades\Excel;

class ExcelHandler implements ImportHandlerImp
{
    /**
     * @var int
     */
    private $count = 0;

    /**
     * @param ImportProductPriceImp $productPriceImp
     * @param string $filePath
     */
    function handle(ImportProductPriceImp $productPriceImp, string $filePath)
    {
        $import = new ProductImport($productPriceImp);
        Excel::import($import, $filePath);
        $this->count = $import->getCounter();
    }

    /**
     * @return int
     */
    function getCountImports(): int
    {
        return $this->count;
    }
}
