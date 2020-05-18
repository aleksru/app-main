<?php


namespace App\Services\Statistic\DeliveryTime;

use App\Services\Statistic\Abstractions\BaseReportTableRender;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\Abstractions\IGeneralStatisticGenerate;
use App\Services\Statistic\GeneralStatistic\GeneralStatistic;
use Carbon\Carbon;

class DeliveryTimeStatistic extends BaseStatistic implements IGeneralStatisticGenerate
{
    protected $repository;
    protected $avgSales;
    protected $sumSales;

    /**
     * DeliveryTimeStatistic constructor.
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        parent::__construct( $dateFrom,  $dateTo);
        $this->repository = new DeliveryTimeStatisticRepository();
    }
    public static function createTableRender(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null) : BaseReportTableRender
    {
        return new DeliveryTimeTableRender( $routeIndex,  $routeDatatable,  $labelHeader,  $name);
    }
    public static function createDefaultTableRender() : BaseReportTableRender
    {
        return new DeliveryTimeTableRender(
            route('statistic.delivery_time'),
            route('statistic.delivery_time.table'),
            'Статистика по времени доставки');
    }

    public function generateAll()
    {
        $this->generateGenerals();
    }

    public function generateGenerals()
    {
        (new GeneralStatistic($this->container, $this))->genAll();
    }

    public function genDone()
    {
        $result = [];
        $items = $this->repository->getDoneCount($this->dateFrom, $this->dateTo);
        foreach ($items as $item){
            $field = new DeliveryTimeStatisticItem($item->delivery_desc);
            $field->setDone($item->cnt);
            $result[] = $field;
        }

        return $result;
    }

    public function genMissed()
    {
        $result = [];
        $items = $this->repository->getMissedCount($this->dateFrom, $this->dateTo);
        foreach ($items as $item){
            $field = new DeliveryTimeStatisticItem($item->delivery_desc);
            $field->setMissed($item->cnt);
            $result[] = $field;
        }

        return $result;
    }

    public function genProfit()
    {
        $result = [];
        $items = $this->repository->getProfitOnDates($this->dateFrom, $this->dateTo);
        foreach ($items as $item){
            $field = new DeliveryTimeStatisticItem($item->delivery_desc);
            $field->setProfit($item->profit);
            $result[] = $field;
        }

        return $result;
    }

    public function genAvgInvoice()
    {
        $result = [];
        $items = $this->getAvgSales();
        foreach ($items as $item){
            $field = new DeliveryTimeStatisticItem($item->delivery_desc);
            $field->setAvgInvoice($item->avg_check);
            $result[] = $field;
        }

        return $result;
    }

    public function genAvgProfit()
    {
        $result = [];
        $items = $this->getAvgSales();
        foreach ($items as $item){
            $field = new DeliveryTimeStatisticItem($item->delivery_desc);
            $field->setAvgProfit($item->avg_profit);
            $result[] = $field;
        }

        return $result;
    }

    public function getSumSales()
    {
        if($this->sumSales === null ){
            $this->sumSales = $this->repository->getCountSalesByDates($this->dateFrom, $this->dateTo);
        }
        return $this->sumSales;
    }

    public function getAvgSales()
    {
        if(! $this->avgSales ){
            $this->genAvgSales();
        }

        return $this->avgSales;
    }

    public function genAvgMainInvoice()
    {
        $result = [];
        $items = $this->repository->getAllSalesOnDates($this->dateFrom, $this->dateTo);
        foreach ($items as $item){
            $field = new DeliveryTimeStatisticItem($item->delivery_desc);
            $field->setAvgMainInvoice($item->avg_sales);
            $result[] = $field;
        }

        return $result;
    }

    protected function genAvgSales()
    {
        $this->avgSales = $this->repository->getAvgSales($this->dateFrom, $this->dateTo);
    }

}