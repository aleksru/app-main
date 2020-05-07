<?php


namespace App\Http\Controllers\Statistics;


use App\Charts\SalesCategoryDateChart;
use App\Charts\SalesDateChart;
use App\Charts\SalesTopProducts;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesStatController extends Controller
{
    public function sales(Request $request)
    {
        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo');
        if( ! $dateFrom ){
            $dateFrom = Carbon::today()->subDays(7);
        }else{
            $dateFrom = Carbon::parse($dateFrom);
        }
        if( ! $dateTo ){
            $dateTo = Carbon::today();
        }else{
            $dateTo = Carbon::parse($dateTo);
        }
        $chart = new SalesDateChart($dateFrom, $dateTo);
        $chart->generateChart();
        $pieCategories = new SalesCategoryDateChart($dateFrom, $dateTo);
        $pieCategories->generateChart();

        $pieTopProducts = new SalesTopProducts($dateFrom, $dateTo);
        $pieTopProducts->generateChart();

        return view('statistic.sales', compact('chart', 'pieCategories', 'pieTopProducts'));
    }
}