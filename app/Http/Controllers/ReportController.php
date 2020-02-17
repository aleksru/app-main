<?php


namespace App\Http\Controllers;


use App\ClientCall;
use App\Enums\MangoCallEnums;
use App\Enums\ProductType;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet1;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet2;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet4;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport\Sheet5;
use App\Models\Operator;
use App\Models\OrderStatus;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('front.reports.resources.index', ['table' => view('front.reports.tables.resources', ['tableName' => 'reportResources']),
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
        $dateFrom = Carbon::parse($request->get('dateFrom') ?? date('Y-m-d H:i:s'));
        $dateTo = Carbon::parse($request->get('dateTo') ?? $request->get('dateFrom') ?? date('Y-m-d H:i:s'));

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
        $idStatusConfirm = OrderStatus::getIdStatusConfirm();
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
        $data->each(function ($item) use (&$results, $idStatusConfirm){
            $results[$item->id] = [
                'count'              => $item->user->createdOrders->count(),
                'order_ids'          => $item->user->createdOrders->pluck('id'),
                'confirm_orders_ids' => $item->user->createdOrders->filter(function ($value, $key) use ($idStatusConfirm){
                    return $value->status_id == $idStatusConfirm;
                })->pluck('id'),
                'operator'           => $item->name,
                'sum'                => $item->user->createdOrders->reduce(function ($prev, $val){
                                            return $prev + $val->fullSum;
                                        }, 0),
                'statuses'           => $item->user->createdOrders->map(function ($val){
                                            return $val->status;
                                        })->filter()->groupBy('id')->map(function ($val){
                                            return $val->count();
                                        }),
            ];
        });

        return view('front.reports.operators_created', compact('statuses', 'results', 'idStatusConfirm'));
    }

    public function reportOperators(Request $request)
    {
        $query = Operator::query();
        if(Auth::user()->isOperator() && !Auth::user()->isSuperOperator() && Auth::user()->account){
            $query = Operator::query()->where('id', Auth::user()->account->id);
        }
        $dateFrom = Carbon::parse($request->get('dateFrom'));
        $dateTo = $request->get('dateTo') ? Carbon::parse($request->get('dateTo')) : null;
        $idStatusConfirm = OrderStatus::getIdStatusConfirm();

        if(!$dateTo){
            $dateTo =  clone $dateFrom;
            $dateTo->addDay();
        }
        $statuses = OrderStatus::all();
        $operators = $query->with([
            'orders' => function($query) use ($dateFrom, $dateTo){
                $union = clone $query;
                $union->whereBetween('confirmed_at', [$dateFrom->toDateString(), $dateTo->toDateString()]);
                $query->whereBetween('created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
                    ->union($union)
                    ->whereNotNull('status_id');
            },
            'orders.realizations',
            'orders.status'
        ])->get()
          ->filter(function ($value){
            return !$value->orders->isEmpty();
        });

        $mains = [
            'main_sum_product'  => 0,
            'main_sum_acc'      => 0,
            'main_sum_all'      => 0,
            'main_count_orders' => 0,
            'main_statuses'    => []
        ];

        $operators->each(function ($item) use ($idStatusConfirm, &$mains) {
            $item->sum_main_product = $item->orders->reduce(function ($prev, $cur) use ($idStatusConfirm) {
                return $prev + ($cur->status_id == $idStatusConfirm ? $cur->getSumProductForType(ProductType::TYPE_PRODUCT) : 0);
            }, 0);
            $item->sum_acc = $item->orders->reduce(function ($prev, $cur) use ($idStatusConfirm) {
                return $prev + ($cur->status_id == $idStatusConfirm ? $cur->getSumProductForType(ProductType::TYPE_ACCESSORY) : 0);
            }, 0);
            $item->count_orders = $item->orders->count();
            $item->count_acc  = $item->orders->reduce(function ($prev, $cur){
                return $prev + $cur->getCountProductForType(ProductType::TYPE_ACCESSORY);
            }, 0);
            $item->statuses_group = $item->orders->map(function ($val){
                return $val->status;
            })->filter()->groupBy('id')->map(function ($val) use (&$mains){
                $cnt = $val->count();
                if(!isset($mains['main_statuses'][$val->first()->id])){
                    $mains['main_statuses'][$val->first()->id] = 0;
                }
                $mains['main_statuses'][$val->first()->id] += $cnt;
                return $cnt;
            });

            $mains['main_sum_product'] += $item->sum_main_product;
            $mains['main_sum_acc'] += $item->sum_acc;
            $mains['main_sum_all'] += $item->sum_acc + $item->sum_main_product;
            $mains['main_count_orders'] += $item->count_orders;
        });

        return view('front.reports.orders-operators',
                        compact('statuses', 'operators', 'idStatusConfirm', 'mains'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function missedCalls(Request $request)
    {
        $dateFrom = Carbon::parse($request->get('dateFrom'));
        $dateTo = $request->get('dateTo') ? Carbon::parse($request->get('dateTo')) : null;

        if(!$dateTo){
            $dateTo =  clone $dateFrom;
            $dateTo->addDay();
        }

        $orders = Order::query()->with('status')
            ->select('orders.*')
            ->join('client_calls', 'orders.entry_id', '=', 'client_calls.entry_id')
            ->whereNotNull('orders.entry_id')
            ->where('client_calls.status_call', MangoCallEnums::CALL_RESULT_MISSED)
            ->where('client_calls.type', ClientCall::incomingCall)
            ->whereBetween('orders.created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->get();
        $orders = $orders->groupBy(function($item, $key){
            return $item->created_at->toDateString();
        });

        return view('front.reports.missed_calls', compact('orders'));
    }
}