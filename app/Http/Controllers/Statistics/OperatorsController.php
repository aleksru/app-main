<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\Operators\OperatorStatistic;

class OperatorsController extends MainReportController
{
    protected function getStatisticField()
    {
        return OperatorStatistic::class;
    }
}