<?php


namespace App\Services\Statistic\ProductCategories;


use App\Services\Statistic\Abstractions\BaseReportTableRender;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\Abstractions\IGeneralStaticItem;
use App\Services\Statistic\GeneralStatistic\GeneralItem;
use App\Services\Statistic\GeneralStatistic\GeneralStatistic;
use Carbon\Carbon;

class ProductCategoriesStatistic extends  BaseStatistic implements IGeneralStaticItem
{
    protected $repository;

    public function __construct(Carbon $dateFrom, Carbon $dateTo)
    {
        parent::__construct($dateFrom, $dateTo);
        $this->repository = new ProductCategoriesStatisticRepository($dateFrom, $dateTo);
    }

    public function generateAll()
    {
        (new GeneralStatistic($this->container, $this->repository, $this))->genAll();
    }

    static function createDataItem($field)
    {
        return new ProductCategoriesStatisticItem($field);
    }

    public static function createTableRender(string $routeIndex, string $routeDatatable,
                                             string $labelHeader = null, string $name = null): BaseReportTableRender
    {
        return new ProductCategoriesStatisticTableRender($routeIndex, $routeDatatable, $labelHeader, $name);
    }

    public static function createDefaultTableRender(): BaseReportTableRender
    {
        return self::createTableRender(
            route('statistic.products_categories'),
            route('statistic.products_categories.table'),
            'Статистика по категориям товара'
        );
    }

    public function createGeneralItem($field): GeneralItem
    {
        return self::createDataItem($field);
    }
}