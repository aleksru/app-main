<?php

namespace App\Http\Controllers\Service\DocumentBuilder\Services;


interface ExcelServiceInterface
{
    public function download($fileName, $templateFile, $dataSource, $outputFormat = 'PDF');
}