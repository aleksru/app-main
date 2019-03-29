<?php


namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;

use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Traits\ExelTemplatorTrait;
use Carbon\Carbon;

class FullReport implements DataInterface
{
    use ExelTemplatorTrait;

    /**
     * Кол-во листов
     * @const int
     */
    const COUNT_SHEETS = 5;

    /**
     * @var Carbon
     */
    private $dateStart;

    /**
     * @var Carbon
     */
    private $dateEnd;

    /**
     * FullReport constructor.
     * @param Carbon $dateStart
     * @param Carbon $dateEnd
     */
    public function __construct (Carbon $dateStart, Carbon $dateEnd)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    /**
     * Внесение данных из заказа в массив
     * @return $this
     */
    public function prepareData()
    {
        for ($i = 1; $i <= self::COUNT_SHEETS; $i++){
            $sheet = "\\App\\Http\\Controllers\\Service\\DocumentBuilder\\OrderDocs\\FullReport\\Sheet{$i}";
            if(class_exists($sheet)){
                $sheet = new $sheet($this->dateStart, $this->dateEnd);
                $this->renderWorkSheet($this->getSheet($i - 1), $sheet->prepareDataSheet());
            }
        }

        return $this;
    }

    /**
     * Получение данных
     * @return array
     */
    public function getData()
    {
       $this->downloadFile($this->getFileName());
    }

    /**
     * Имя файла
     * @return string
     */
    public function getFileName() : string
    {
        return 'Отчет от '.(date("d.m.Y")).'.xlsx';
    }

    /**
     * @return string
     */
    public function getTemplatePath() : string
    {
        return  storage_path('app' . DIRECTORY_SEPARATOR . 'exel_templates' . DIRECTORY_SEPARATOR . 'full_report.xlsx');
    }
}