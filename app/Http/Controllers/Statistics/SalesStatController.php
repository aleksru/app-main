<?php


namespace App\Http\Controllers\Statistics;


use App\Charts\SalesDateChart;
use App\Enums\StatusResults;
use App\Http\Controllers\Controller;
use App\Repositories\StatisticRepository;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SalesStatController extends Controller
{
    public function sales()
    {
        $chart = new SalesDateChart(Carbon::today()->subDays(57), Carbon::today());
        $chart->addSuccessSales()
            ->addRefusalSales()
            ->addAllSales();


        return view('statistic.sales', compact('chart'));
    }
}