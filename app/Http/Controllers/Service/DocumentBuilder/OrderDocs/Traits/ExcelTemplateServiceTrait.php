<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Traits;

use App\Http\Controllers\Service\DocumentBuilder\ExcelTemplateService;

trait ExcelTemplateServiceTrait
{
    /**
     * @var ExcelTemplateService
     */
    protected $excelTemplateService;

    /**
     * @return ExcelTemplateService
     */
    protected function getExcelService() : ExcelTemplateService
    {
        if(!$this->excelTemplateService) {
            $this->excelTemplateService = new ExcelTemplateService();
        }

        return $this->excelTemplateService;
    }

    /**
     * Render & download document
     *
     * @param string $fileName
     * @param string $template
     * @param array $data
     * @param string $format
     */
    public function download(string $fileName, string $template, array $data, string $format = 'XLSX') : void
    {
        $this->getExcelService()->download($fileName, $template, $data, $format);
    }
}