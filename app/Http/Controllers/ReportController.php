<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet1;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet2;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet4;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet5;
use App\Models\Operator;
use App\Models\OrderStatus;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function utmReport(Request $request)
    {
        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo') ? $request->get('dateTo') : $dateFrom;
        $storesIds = $request->get('store_ids');
        $orders = [];
        $statuses = OrderStatus::all();
        $res = DB::table('orders')->selectRaw('utm_source, status_id, count(*) as cnt')
                    ->whereNotNull('utm_source')
                    ->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->groupBy('utm_source', 'status_id');
        if($storesIds){
            $res->whereIn('store_id', $storesIds);
        }
        $res->get()
            ->each(function ($item) use (&$orders){
                $orders[$item->utm_source][$item->status_id] = $item->cnt;
            });

        return view('front.reports.utm', compact('orders', 'statuses'));
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function utmStatus(Request $request)
    {
        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo') ? $request->get('dateTo') : $dateFrom;
        $storesIds = $request->get('store_ids');
        $statuses = OrderStatus::all();
        $orders = Order::with('status')
            ->selectRaw('status_id, utm_source, COUNT(*) as cnt')
            ->whereNotNull('utm_source')
            ->whereNotNull('status_id')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('status_id', 'utm_source');
        if($storesIds){
            $orders->whereIn('store_id', $storesIds);
        }
        $orders = $orders->get()->groupBy('utm_source');

        return view('front.reports.utm_status', compact('orders', 'statuses'));
    }

    /**
     * Созданные оператором заказы
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function operatorCreatedOrders(Request $request)
    {
        $dateFrom = Carbon::parse($request->get('dateFrom'));
        $dateTo = $request->get('dateTo') ? Carbon::parse($request->get('dateTo')) : null;
        if(!$dateTo){
            $dateTo =  clone $dateFrom;
            $dateTo->addDay();
        }
        $statuses = OrderStatus::all();
        $data = Operator::query()
                    ->has('user')
                    ->with(['user.createdOrders' => function($query) use ($dateFrom, $dateTo){
                        $query->whereBetween('created_at', [$dateFrom, $dateTo]);
                     },
                    'user.createdOrders.realizations',
                    'user.createdOrders.status',
                    ])->get();

        $data = $data->reject(function ($item){
            return $item->user->createdOrders->isEmpty();
        });
        $results = [];

        $data->each(function ($item) use (&$results){
            $results[$item->id] = [
                'count'     => $item->user->createdOrders->count(),
                'order_ids' => $item->user->createdOrders->pluck('id'),
                'operator'  => $item->name,
                'sum'       => $item->user->createdOrders->reduce(function ($prev, $val){
                                    return $prev + $val->fullSum;
                                }, 0),
                'statuses'  => $item->user->createdOrders->map(function ($val){
                    return $val->status;
                })->filter()->groupBy('id')->map(function ($val){
                    return $val->count();
                }),
            ];
        });

        return view('front.reports.operators_created', compact('statuses', 'results'));
    }
}