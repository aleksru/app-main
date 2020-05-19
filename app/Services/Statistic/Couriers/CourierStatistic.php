<?php


namespace App\Services\Statistic\Couriers;

use App\Services\Statistic\Abstractions\BaseReportTableRender;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\GeneralStatistic\GeneralStatistic;
use Carbon\Carbon;

class CourierStatistic extends BaseStatistic
{
    protected $repository;

    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        parent::__construct($dateFrom, $dateTo);
        $this->repository = new CouriersStatisticRepository($dateFrom, $dateTo);
    }

    public function generateAll()
    {
        (new GeneralStatistic($this->container, $this->repository, $this))->genAll();
        $this->genCourierPayment();
        $this->genTotalPriceSales();
        $this->genTotalPriceOptSales();
    }


    public function genCourierPayment()
    {
        $items = $this->repository->getCourierPayment();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setCourierPayment($item->{CourierDBContract::SUM_COURIER_PAYMENT});
        }
    }

    public function genTotalPriceSales()
    {
        $items = $this->repository->getTotalPriceSale();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setSumPriceSales($item->{CourierDBContract::TOTAL_PRICE_SALE});
        }
    }

    public function genTotalPriceOptSales()
    {
        $items = $this->repository->getTotalPriceOpt();
        foreach ($items as $item) {
            $field = $this->getOrCreateFieldOnContainer($item->{$this->repository->getFieldName()});
            $field->setSumPriceOpt($item->{CourierDBContract::TOTAL_PRICE_OPT});
        }
    }
    /**
     * @param string $routeIndex
     * @param string $routeDatatable
     * @param string|null $labelHeader
     * @param string|null $name
     * @return BaseReportTableRender
     */
    public static function createTableRender(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null) : BaseReportTableRender
    {
        return new CourierStatisticTableRender( $routeIndex,  $routeDatatable,  $labelHeader,  $name);
    }

    /**
     * @return BaseReportTableRender
     */
    public static function createDefaultTableRender() : BaseReportTableRender
    {
        return self::createTableRender(
            route('statistic.couriers'),
            route('statistic.couriers.table'),
                'Статистика по курьерам'
        );
    }

    public static function createDataItem($field): CourierStatisticItem
    {
        return new CourierStatisticItem($field);
    }

    protected function getOrCreateFieldOnContainer($key) : CourierStatisticItem
    {
        return parent::getOrCreateFieldOnContainer($key);
    }
}