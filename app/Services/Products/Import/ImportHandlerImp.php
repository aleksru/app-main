<?php


namespace App\Services\Products\Import;


interface ImportHandlerImp
{
    function handle(ImportProductPriceImp $productPriceImp, string $filePath);
    function getCountImports(): int;
}
