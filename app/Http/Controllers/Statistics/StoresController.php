<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\Stores\StoresStatistic;

class StoresController extends MainReportController
{
    protected function getStatisticField()
    {
        return StoresStatistic::class;
    }
}