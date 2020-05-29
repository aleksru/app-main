<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\PickUp\PickUpStatistic;

class PickUpController extends MainReportController
{
    protected function getStatisticField()
    {
        return PickUpStatistic::class;
    }
}