<?php


namespace App\Http\Controllers\Statistics\ReportTables;


use App\Http\Controllers\Controller;
use App\Services\Statistic\Abstractions\BaseStatistic;
use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class MainReportController extends Controller
{
    public function index()
    {
        $field = $this->getStatisticField();
        $DTRender = $field::createDefaultTableRender();

        return view('statistic.base.default_index_page', compact('DTRender'));
    }

    public function datatable(Request $request)
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
        $field = $this->createStatisticField($dateFrom, $dateTo);
        $field->generateAll();

        return datatables()
            ->collection($field->getContainerOnCollection())
            ->make(true);
    }

    abstract protected function getStatisticField();

    protected function createStatisticField(Carbon $dateFrom , Carbon $dateTo) : BaseStatistic
    {
        $field = $this->getStatisticField();
        return new $field($dateFrom, $dateTo);
    }
}
