<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\ProductCategories\ProductCategoriesStatistic;

class ProductsCategoriesController extends MainReportController
{
    protected function getStatisticField()
    {
        return ProductCategoriesStatistic::class;
    }
}