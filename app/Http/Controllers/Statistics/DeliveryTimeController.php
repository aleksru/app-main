<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\DeliveryTime\DeliveryTimeStatistic;


class DeliveryTimeController extends MainReportController
{
    protected function getStatisticField()
    {
        return DeliveryTimeStatistic::class;
    }
}