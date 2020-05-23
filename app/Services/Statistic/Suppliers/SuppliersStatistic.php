<?php


namespace App\Services\Statistic\Suppliers;


use App\Services\Statistic\Abstractions\BaseReportTableRender;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\Abstractions\IGeneralStaticItem;
use App\Services\Statistic\GeneralStatistic\GeneralItem;
use App\Services\Statistic\GeneralStatistic\GeneralStatistic;
use Carbon\Carbon;

class SuppliersStatistic extends BaseStatistic implements IGeneralStaticItem
{
    protected $repository;

    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        parent::__construct($dateFrom, $dateTo);
        $this->repository = new SuppliersStatisticRepository($dateFrom, $dateTo);
    }

    public function generateAll()
    {
        (new GeneralStatistic($this->container, $this->repository, $this))->genAll();
    }

    static function createDataItem($field)
    {
        return new SuppliersStatisticItem($field);
    }

    public static function createTableRender(string $routeIndex, string $routeDatatable,
                                             string $labelHeader = null, string $name = null): BaseReportTableRender
    {
        return new SuppliersStatisticTableRender($routeIndex, $routeDatatable, $labelHeader, $name);
    }

    public static function createDefaultTableRender(): BaseReportTableRender
    {
        return self::createTableRender(
            route('statistic.suppliers'),
                route('statistic.suppliers.table'),
                'Статистика по поставщикам');
    }

    public function createGeneralItem($field): GeneralItem
    {
        return self::createDataItem($field);
    }
}