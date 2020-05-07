<?php


namespace App\Http\Controllers\Statistics;


use App\Charts\SalesDateChart;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class SalesStatController extends Controller
{
    public function sales()
    {
        $chart = new SalesDateChart(Carbon::today()->subDays(57), Carbon::today());
        $chart->generateChart();

        return view('statistic.sales', compact('chart'));
    }
}