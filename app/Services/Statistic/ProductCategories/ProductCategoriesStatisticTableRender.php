<?php


namespace App\Services\Statistic\ProductCategories;


use App\Services\Statistic\GeneralStatistic\GeneralReportTableRender;

class ProductCategoriesStatisticTableRender extends GeneralReportTableRender
{
    public function __construct(string $routeIndex, string $routeDatatable, string $labelHeader = null, string $name = null)
    {
        parent::__construct($routeIndex,  $routeDatatable,  $labelHeader,  ($name === null) ? 'products-categories' : $name);
    }
}