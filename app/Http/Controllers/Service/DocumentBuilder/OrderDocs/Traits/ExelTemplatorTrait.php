<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Traits;


use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait ExelTemplatorTrait
{
    /**
     * @var null|Spreadsheet
     */
    private $spreadsheet;

    /**
     * Скачивание файла
     *
     * @param string $fileName
     */
    public function downloadFile(string $fileName) : void
    {
        PhpExcelTemplator::outputSpreadsheetToFile($this->getSpreadsheet(), $fileName);
        exit;
    }

    /**
     * Сохранение файла
     *
     * @param string $fileName
     */
    public function saveFile(string $fileName) : void
    {
        PhpExcelTemplator::saveSpreadsheetToFile($this->getSpreadsheet(), $fileName);
    }

    /**
     * Получение док-та
     *
     * @return Spreadsheet
     */
    protected function getSpreadsheet() : Spreadsheet
    {
        if(!$this->spreadsheet) {
            $this->spreadsheet = IOFactory::load($this->getExelTemplate());
        }

        return $this->spreadsheet;
    }

    /**
     * Устанавливает активным выбранный лист и возвращает его
     *
     * @param int $numb
     * @return Worksheet
     */
    protected function getSheet(int $numb) : Worksheet
    {
        $this->getSpreadsheet()->setActiveSheetIndex($numb);

        return $this->getSpreadsheet()->getSheet($numb);
    }

    /**
     * Получение переменных в шаблоне
     *
     * @return array
     */
    protected function getTemplateVars() : array
    {
        return $this->getSpreadsheet()->getActiveSheet()->toArray();
    }

    /**
     * Генерация листа
     *
     * @param Worksheet $sheet
     * @param array $params
     */
    protected function renderWorkSheet(Worksheet $sheet, array $params) : void
    {
       PhpExcelTemplator::renderWorksheet($sheet, $this->getTemplateVars(), $params);
    }

    /**
     * Получение шаблона
     *
     * @return string
     */
    protected function getExelTemplate() : string
    {
        return $this->getTemplatePath();
    }
}