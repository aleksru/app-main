<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\DeliveryTime\DeliveryTimeStatistic;
use Carbon\Carbon;


class DeliveryTimeController extends MainReportController
{
    protected function getStatisticField()
    {
        return DeliveryTimeStatistic::class;
    }

    protected function createStatisticField(Carbon $dateFrom , Carbon $dateTo) : BaseStatistic
    {
        $field = $this->getStatisticField();
        return new $field($dateFrom, $dateTo);
    }
}