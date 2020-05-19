<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\Couriers\CourierStatistic;


class CouriersController extends MainReportController
{
    protected function getStatisticField()
    {
        return CourierStatistic::class;
    }
}