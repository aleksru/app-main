<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet1;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet2;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet4;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet5;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function operators()
    {
        return view('front.reports.index', ['table' => view('front.reports.tables.operators', ['tableName' => 'reportOperators']),
                    'tableName' => 'reportOperators']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function days()
    {
        return view('front.reports.index', ['table' => view('front.reports.tables.days', ['tableName' => 'reportDays']),
            'tableName' => 'reportDays']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function products()
    {
        return view('front.reports.index', ['table' => view('front.reports.tables.products', ['tableName' => 'reportProducts']),
            'tableName' => 'reportProducts']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resources()
    {
        return view('front.reports.index', ['table' => view('front.reports.tables.resources', ['tableName' => 'reportResources']),
            'tableName' => 'reportResources']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function operatorsDatatable(Request $request)
    {
        $dateFrom = Carbon::parse($request->get('dateFrom') ?? date('Y-m-d'));
        $dateTo = Carbon::parse($request->get('dateTo') ?? $request->get('dateFrom') ?? date('Y-m-d'));
        $table = $request->get('tableName');
        $data = [];

        if($table === "reportOperators"){
            $data = new Sheet1($dateFrom, $dateTo);
            $data = $data->prepareDataSheet();
        }
        if($table === 'reportDays'){
            $data = new Sheet2($dateFrom, $dateTo);
            $data = $data->prepareDataSheet();
        }

        if($table === 'reportProducts'){
            $data = new Sheet4($dateFrom, $dateTo);
            $data = $data->prepareDataSheet();
        }

        if($table === 'reportResources'){
            $data = new Sheet5($dateFrom, $dateTo);
            $data = $data->prepareDataSheet();
        }

        $result = [];
        foreach ($data as $key => $item){
            for($i = 0; $i < count($item); $i++) {
                $result[$i][$key] = $item[$i];
            }
        }

        return datatables()->collection(collect($result))->toJson();
    }
}