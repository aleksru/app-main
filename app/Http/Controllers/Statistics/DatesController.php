<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\Dates\DatesStatistic;

class DatesController extends MainReportController
{
    protected function getStatisticField()
    {
        return DatesStatistic::class;
    }
}