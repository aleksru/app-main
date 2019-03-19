<?php

namespace App\Http\Controllers\Service\DocumentBuilder;


use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\DataDocs\DataInterface;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\GenerateDocumentInterface;
use App\Http\Controllers\Service\DocumentBuilder\Services\CustomTemplateService;
use App\Http\Controllers\Service\DocumentBuilder\Services\ExcelServiceInterface;
use App\Http\Controllers\Service\DocumentBuilder\Services\ExcelTemplateService;

class Builder
{
    /**
     * Формат файла
     */
    const FORMAT_DOC_EXEL = 'XLSX';

    const SERVICES = [
        'invoice' => 'market_check.xlsx',
        'route_map' => 'route_map.xlsx',
        'day_report' => 'every_day_report.xlsx'
    ];

    /**
     * @param DataInterface $data
     */
    public function download(DataInterface $data, string $type)
    {
        $service = self::getExcelService($type);

        $service->download(
            $data->getFileName(),
            $data->getTemplate(),
            $data->prepareData()->getData(),
            self::FORMAT_DOC_EXEL
        );
    }

    /**
     * @param $type
     * @return ExcelServiceInterface
     */
    public static function getExcelService($type):ExcelServiceInterface
    {
        switch ($type){
            case 'full_report':
                return new CustomTemplateService();
            default:
                return new ExcelTemplateService();
        }

    }
}