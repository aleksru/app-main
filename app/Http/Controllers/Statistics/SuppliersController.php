<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\Suppliers\SuppliersStatistic;

class SuppliersController extends MainReportController
{
    protected function getStatisticField()
    {
        return SuppliersStatistic::class;
    }
}