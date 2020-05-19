<?php


namespace App\Services\Statistic\DeliveryTime;

use App\Services\Statistic\Abstractions\BaseReportTableRender;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\Abstractions\IGeneralStaticItem;
use App\Services\Statistic\Abstractions\IGeneralStatisticGenerate;
use App\Services\Statistic\GeneralStatistic\GeneralItem;
use App\Services\Statistic\GeneralStatistic\GeneralStatistic;
use Carbon\Carbon;

class DeliveryTimeStatistic extends BaseStatistic implements IGeneralStaticItem
{
    protected $repository;

    /**
     * DeliveryTimeStatistic constructor.
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     */
    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        parent::__construct( $dateFrom,  $dateTo);
        $this->repository = new DeliveryTimeStatisticRepository($dateFrom, $dateTo);
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
        (new GeneralStatistic($this->container, $this->repository, $this))->genAll();
    }

    static function createDataItem($field) : DeliveryTimeStatisticItem
    {
        return new DeliveryTimeStatisticItem($field);
    }

    protected function getOrCreateFieldOnContainer($key) : DeliveryTimeStatisticItem
    {
        return parent::getOrCreateFieldOnContainer($key);
    }

    public function createGeneralItem($field): GeneralItem
    {
        return self::createDataItem($field);
    }
}