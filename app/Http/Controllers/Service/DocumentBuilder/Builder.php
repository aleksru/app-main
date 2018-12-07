<?php

namespace App\Http\Controllers\Service\DocumentBuilder;


class Builder
{
    /**
     * Формат файла
     */
    const FORMAT_DOC_EXEL = 'XLSX';

    /**
     * Путь к шаблонам
     * @var string
     */
    private static $storageTemplates;

    /**
     * @var ExcelTemplateService
     */
    private $exelDocCreator;

    /**
     * Массив типа документа => шаблон
     * @var array
     */
    private $templates = [
        'invoice' => 'market_check.xlsx',
    ];

    /**
     * Builder constructor.
     * @param ExcelTemplateService $excelTemplateService
     */
    public function __construct (ExcelTemplateService $excelTemplateService)
    {
        self::$storageTemplates = storage_path('app'.DIRECTORY_SEPARATOR.'exel_templates'.DIRECTORY_SEPARATOR);
        $this->exelDocCreator = $excelTemplateService;
    }

    /**
     * @param DataInterface $data
     * @param string $type
     */
    public function download(DataInterface $data, string $type)
    {
        $docType = $this->templates[$type];

        $this->exelDocCreator->download(
            $data->getFileName(),
            self::$storageTemplates.$docType,
            $data->prepareData()->getData(),
            self::FORMAT_DOC_EXEL
        );
    }
}