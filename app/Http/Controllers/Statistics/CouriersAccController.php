<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\CouriersAcc\CouriersAccStatistic;

class CouriersAccController extends MainReportController
{
    protected function getStatisticField()
    {
        return CouriersAccStatistic::class;
    }
}