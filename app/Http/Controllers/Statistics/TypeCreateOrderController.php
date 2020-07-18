<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Statistics\ReportTables\MainReportController;
use App\Services\Statistic\Abstractions\BaseStatistic;
use App\Services\Statistic\TypeCreateOrder\TypeCreateOrderStatistic;
use App\Store;
use Carbon\Carbon;

class TypeCreateOrderController extends MainReportController
{
    protected function getStatisticField()
    {
        return TypeCreateOrderStatistic::class;
    }

    protected function createStatisticField(Carbon $dateFrom , Carbon $dateTo) : BaseStatistic
    {
        $field = $this->getStatisticField();
        return new $field($dateFrom, $dateTo, Store::find(request()->get('store_id')));
    }
}
