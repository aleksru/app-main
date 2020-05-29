<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\ProductStores\ProductStoresStatistic;

class ProductStoreController extends MainReportController
{
    protected function getStatisticField()
    {
        return ProductStoresStatistic::class;
    }
}