<?php

namespace App\Http\Controllers\Service\DocumentBuilder\Services;


use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;

class CustomTemplateService implements ExcelServiceInterface
{
    /**
     * @param $fileName
     * @param $templateFile
     * @param $dataSource
     * @param string $outputFormat
     */
    public function download($fileName, $templateFile, $dataSource, $outputFormat = 'PDF')
    {
        PhpExcelTemplator::outputToFile($templateFile, $fileName, $dataSource);
    }
}