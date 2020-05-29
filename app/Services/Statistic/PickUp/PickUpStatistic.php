<?php


namespace App\Services\Statistic\PickUp;


use App\Services\Statistic\Abstractions\BaseReportTableRender;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\Abstractions\IGeneralStaticItem;
use App\Services\Statistic\GeneralStatistic\GeneralItem;
use App\Services\Statistic\GeneralStatistic\GeneralStatistic;
use Carbon\Carbon;

class PickUpStatistic extends BaseStatistic implements IGeneralStaticItem
{
    protected $repository;

    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        parent::__construct($dateFrom, $dateTo);
        $this->repository = new PickUpStatisticRepository($dateFrom, $dateTo);
    }

    static function createDataItem($field)
    {
        return new PickUpStatisticItem($field);
    }

    public function generateAll()
    {
        (new GeneralStatistic($this->container, $this->repository, $this))->genAll();
    }

    public static function createTableRender(string $routeIndex, string $routeDatatable,
                                             string $labelHeader = null, string $name = null): BaseReportTableRender
    {
        return new PickUpStatisticTableRender($routeIndex, $routeDatatable, $labelHeader, $name);
    }

    public static function createDefaultTableRender(): BaseReportTableRender
    {
       return self::createTableRender(
           route('statistic.pickup'),
               route('statistic.pickup.table'),
               'Статистика по самовывозам');
    }

    public function createGeneralItem($field): GeneralItem
    {
        return self::createDataItem($field);
    }
}