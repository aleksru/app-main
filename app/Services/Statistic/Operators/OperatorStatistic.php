<?php


namespace App\Services\Statistic\Operators;


use App\Services\Statistic\Abstractions\BaseReportTableRender;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\Abstractions\IGeneralStaticItem;
use App\Services\Statistic\GeneralStatistic\GeneralItem;
use App\Services\Statistic\GeneralStatistic\GeneralStatistic;
use Carbon\Carbon;

class OperatorStatistic extends BaseStatistic implements IGeneralStaticItem
{
    protected $repository;

    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        parent::__construct($dateFrom, $dateTo);
        $this->repository = new OperatorStatisticRepository($dateFrom, $dateTo);
    }

    public function generateAll()
    {
        (new GeneralStatistic($this->container, $this->repository, $this))->genAll();
        $this->genTotalSumSaleAcc();
        $this->genCountSalesAirPods();
        $this->genSumSalesAirPods();
    }

    static function createDataItem($field)
    {
        return new OperatorStatisticItem($field);
    }

    public static function createTableRender(string $routeIndex, string $routeDatatable,
                                             string $labelHeader = null, string $name = null): BaseReportTableRender
    {
        return new OperatorsStatisticTableRender($routeIndex, $routeDatatable, $labelHeader, $name);
    }

    public static function createDefaultTableRender(): BaseReportTableRender
    {
        return self::createTableRender(
            route('statistic.operators'),
                route('statistic.operators.table'),
                'Статистика операторов');
    }

    public function createGeneralItem($field): GeneralItem
    {
        return self::createDataItem($field);
    }

    public function genTotalSumSaleAcc()
    {
        $items = $this->repository->getTotalSumSalesAccessory();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setTotalSumSalesAccessory($item->{OperatorsDBContract::SUM_SALES_ACC});
        }
    }

    public function genCountSalesAirPods()
    {
        $items = $this->repository->getCountSaleAirPods();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setCountSalesAirPods($item->{OperatorsDBContract::COUNT_SALES_AIRPODS});
        }
    }

    public function genSumSalesAirPods()
    {
        $items = $this->repository->getSumSaleAirPods();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setTotalSumSalesAirPods($item->{OperatorsDBContract::SUM_SALES_AIRPODS});
        }
    }

    protected function getOrCreateFieldOnContainer($key) :  OperatorStatisticItem
    {
        return parent::getOrCreateFieldOnContainer($key);
    }
}