<?php


namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;


use App\Models\Courier;
use Carbon\Carbon;

class Warranty extends BaseReport
{
    /**
     * @var Courier
     */
    private $courier;

    /**
     * @var RouteMap
     */
    private $routeMap;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Warranty constructor.
     * @param Courier $courier
     */
    public function __construct (Courier $courier, RouteMap $routeMap)
    {
        $this->courier = $courier;
        $this->routeMap = $routeMap;
    }

    /**
     * Внесение данных из заказа в массив
     * @return $this
     */
    public function prepareData()
    {
        $this->data = array_merge($this->data, $this->routeMap->prepareData()->getArrayData());
        $this->data['courier_birth_day'] = $this->courier->birth_day->format('d.m.Y') ?? '';
        $this->data['courier_passport_date'] = $this->courier->passport_date->format('d.m.Y') ?? '';
        $this->data['courier_passport_number'] = $this->courier->passport_number ?? '';
        $this->data['courier_passport_address'] = $this->courier->passport_address ?? '';
        $this->data['courier_passport_issued_by'] = $this->courier->passport_issued_by ?? '';
        $this->data['date'] = Carbon::today()->format('d.m.Y') ?? '';
        $this->setCorpInfo();

        return $this;
    }

    /**
     * Имя файла
     * @return string
     */
    public function getFileName() : string
    {
        return 'Расписка '.$this->courier->name.' от '.date("d.m.Y").'.xlsx';
    }

    /**
     * @return string
     */
    public function getTemplatePath() : string
    {
        return  storage_path('app' . DIRECTORY_SEPARATOR . 'exel_templates' . DIRECTORY_SEPARATOR . 'courier_obligation.xlsx');
    }
}