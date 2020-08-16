<?php

namespace App\Http\Controllers;


use App\Events\LogistTableUpdateEvent;
use App\Events\RealizationCopyLogistEvent;
use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Requests\DataTableRequest;
use App\Jobs\SendLogistGoogleTable;
use App\Jobs\SendOrderQuickJob;
use App\Models\Courier;
use App\Models\FailDeliveryDate;
use App\Models\Logist;
use App\Models\OrderStatus;
use App\Models\OtherStatus;
use App\Models\Realization;
use App\Order;
use App\Repositories\DeliveryPeriodsRepository;
use App\Services\Google\Sheets\Data\OrderLogistData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LogisticController extends Controller
{
    const COLUMNS_SIMPLE_TABLE = [
            'date_delivery' => [
                'name' => 'Дата доставки',
                'width' => '1%',
                'searchable' => true,
            ],
            'delivery_time' => [
                'name' => 'Время доставки',
                'width' => '1%',
                'searchable' => false,
                'orderable' => true
            ],
            'id' => [
                'name' => 'Номер заказа',
                'width' => '1%',
                'searchable' => true,
                'orderable' => true
            ],
            'comment' => [
                'name' => 'Коммент КЦ',
                'width' => '1%',
                'searchable' => false,
                'orderable' => true
            ],
            'comment_stock' => [
                'name' => 'Коммент СКЛАД',
                'width' => '3%',
                'searchable' => false,
                'orderable' => true
            ],
            'comment_logist' => [
                'name' => 'Коммент ЛОГ',
                'width' => '3%',
                'searchable' => false,
                'orderable' => true
            ],
            'status_stock' => [
                'name' => 'Статус Склад',
                'width' => '5%',
                'searchable' => true,
                'orderable' => false
            ],
            'status_logist' => [
                'name' => 'Причина отказа',
                'width' => '5%',
                'searchable' => true,
                'orderable' => true
            ],
            'address' => [
                'name' => 'Адрес',
                'width' => '5%',
                'searchable' => true,
                'orderable' => true
            ],
            'client_phone' => [
                'name' => 'Телефон',
                'width' => '1%',
                'searchable' => true,
                'orderable' => true
            ],
            'courier_name' => [
                'name' => 'Курьер',
                'width' => '6%',
                'searchable' => true,
            ],

            'products' => [
                'name' => 'Товары',
                'width' => '7%',
                'searchable' => false,
                'orderable' => true
            ],

            'sum_price_opt' => [
                'name' => 'Закупка',
                'width' => '1%',
                'searchable' => false,
                'orderable' => true
            ],

            'sum_sales' => [
                'name' => 'Продажа',
                'width' => '1%',
                'searchable' => false,
                'orderable' => true
            ],

            'sum_profit' => [
                'name' => 'Прибыль',
                'width' => '1%',
                'searchable' => false,
                'orderable' => true
            ],

            'imei' => [
                'name' => 'IMEI',
                'width' => '5%',
                'searchable' => true,
                'orderable' => true
            ],

            'btn_details' => [
                'name' => '-',
                'width' => '1%',
                'searchable' => false,
                'orderable' => true
            ],
            'checkbox' => [
                'name' => '',
                'width' => '1%',
                'searchable' => false,
                'orderable' => true
            ],
        ];

    public static function getNameColumnOnIndex(int $index)
    {
        $keys = array_keys(self::COLUMNS_SIMPLE_TABLE);
        return $keys[$index];
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view', Logist::class);

        return view('front.orders.orders', ['routeDatatable' => route('logistics.datatable')]);
    }

    /**
     * @param OrdersDatatable $ordersDatatable
     * @return mixed
     */
    public function datatable(OrdersDatatable $ordersDatatable)
    {
        $statusIds = OrderStatus::getIdsStatusesForStock()->merge(OrderStatus::getIdsStatusesForLogistic());
        $builder = $ordersDatatable->getOrderQuery();
        $builder->whereIn('orders.status_id', $statusIds);
        $ordersDatatable->setQuery($builder);

        return $ordersDatatable->datatable();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function simpleOrders()
    {
        $this->authorize('view', Logist::class);
        $couriersSelect = [];
        $couriersSelect[] = ['id' => 0, 'text' => 'Без курьера'];
        $couriersSelect = array_merge($couriersSelect, Courier::select('id', 'name as text')->orderBy('name')->get()->toArray());

        $statusesStockSelect = [];
        $statusesStockSelect[] = ['id' => 0, 'text' => 'Без статуса'];
        $statusesStockSelect = array_merge($statusesStockSelect, OtherStatus::typeStockStatuses()->select('id', 'name as text')->orderBy('name')->get()->toArray());

        $statusesLogisticSelect = [];
        $statusesLogisticSelect[] = ['id' => 0, 'text' => 'Без статуса'];
        $statusesLogisticSelect = array_merge($statusesLogisticSelect, OtherStatus::typeLogisticStatuses()->select('id', 'name as text')->orderBy('name')->get()->toArray());

        return view('front.logistic.simple_orders', [
            'routeDatatable'         => route('logistics.simple.orders.datatable'),
            'couriersSelect'         => $couriersSelect,
            'statusesStockSelect'    => $statusesStockSelect,
            'statusesLogisticSelect' => $statusesLogisticSelect
        ]);
    }

    /**
     * @return mixed
     */
    public function simpleOrdersDatatable(DataTableRequest $request)
    {
        $dateDeliverIndexColumn = $request->getColumnIndexByName('date_delivery');
        $isDisabledPages = false;
        $isDeliverColumnSearchable = $request->isColumnSearchable($dateDeliverIndexColumn);
        if($dateDeliverIndexColumn !== null && $isDeliverColumnSearchable){
            $columnKeyword = $request->columnKeyword($dateDeliverIndexColumn);
            $columnKeywordArr = explode(',', $columnKeyword);
            if(count($columnKeywordArr) === 1){
                $isDisabledPages = true;
            }
        }

        $statusIds = Cache::remember('logistics.status.ids', Carbon::now()->addHours(4), function () {
            return OrderStatus::getIdsStatusesForLogistic();
        });
        $user = Auth::user();
        $accessCitiesByLogistIds = Cache::remember('ACCESS_CITIES_LOGIST_' . $user->id, Carbon::now()->addHours(4), function () use ($user){
            return ($user->isLogist() && $user->account) ?
                $user->account->cities->pluck('id') : collect();
        });
        $stockStatuses = OtherStatus::typeStockStatuses()->get();

        $orders = Order::with([
            'status',
            'store',
            'client.additionalPhones',
            'courier',
            'metro',
            'deliveryPeriod',
            'deliveryType',
            'operator',
            'products',
            'logisticStatus',
            'realizations' => function($query){
                $query->withoutRefusal();
            },
            'realizations.product',
            'stockStatus'
        ])->selectRaw('orders.*, delivery_periods.timeFrom, couriers.name as courier_name')
            ->leftJoin('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->leftJoin('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id')
            ->whereIn('orders.status_id', $statusIds);
//            ->orderBy('orders.updated_at', 'DESC');
//            ->orderBy('orders.date_delivery', 'ASC')
//            ->orderBy('delivery_periods.timeFrom', 'ASC');
        if( ! $isDeliverColumnSearchable ){
            $orders->where('orders.date_delivery', '>=', Carbon::now()->subDays(7)->toDateString());
        }else if($isDeliverColumnSearchable && ! $user->isArchiveRealizationsRole()){
            $orders->where('orders.date_delivery', '>=', Carbon::now()->subDays(7)->toDateString());
        }
        if( ! $accessCitiesByLogistIds->isEmpty() ) {
            $orders->whereIn('orders.city_id', $accessCitiesByLogistIds);
        }
        $datatable = datatables()->of($orders)
            ->filterColumn('client_phone', function (Builder $query, $keyword) use ($orders){
                if (preg_match('/[0-9]{4,}/', $keyword)){
                    $orders->leftJoin('clients', 'orders.client_id', '=', 'clients.id');
                     return $query->OrWhereRaw('clients.phone like ?', "%{$keyword}%");
                }
            })
            ->filterColumn('name_customer', function ($query, $keyword) {
                if (preg_match('/[A-Za-zА-Яа-я]{3,}/', $keyword)) {
                    return $query->whereRaw('LOWER(client.name) like ?', "%{$keyword}%");
                }
            })
            ->filterColumn('imei', function ($query, $keyword) use ($orders){
                $orders->leftJoin('realizations', 'orders.id', '=', 'realizations.order_id');
                return $query->whereRaw('LOWER(realizations.imei) like ?', "%{$keyword}%");
            })
            ->filterColumn('courier_name', function ($query, $keyword) use ($orders){
                if((int)$keyword > 0){
                    //$orders->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id');
                    return $query->where('couriers.id', $keyword);
                }else{
                    return $query->whereNull('orders.courier_id');
                }

            })
            ->filterColumn('address', function ($query, $keyword){
                return $query->whereRaw('LOWER(orders.address_street) like ?', "%{$keyword}%");
            })
            ->filterColumn('date_delivery', function ($query, $keyword){
                if (preg_match('/\d{4}.\d{2}.\d{2}/', $keyword)){
                    $dates = explode(',', $keyword);
                    if(count($dates) === 1){
                        return $query->whereDate('orders.date_delivery', $dates[0]);
                    }
                    if(count($dates) === 2){
                        return $query->whereBetween('orders.date_delivery', [$dates[0], $dates[1]]);
                    }
                }
            })
            ->filterColumn('status_stock', function ($query, $keyword) use ($orders){
                if((int)$keyword > 0){
                    //$orders->leftJoin('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id');
                    return $query->where('other_statuses.id', $keyword);
                }else{
                    return $query->whereNull('orders.stock_status_id');
                }
            })
            ->filterColumn('status_logist', function ($query, $keyword) use ($orders){
                if((int)$keyword > 0){
                    $orders->leftJoin('other_statuses as other_statuses_logist', 'orders.logistic_status_id', '=', 'other_statuses_logist.id');
                    return $query->where('other_statuses_logist.id', $keyword);
                }else{
                    return $query->whereNull('orders.logistic_status_id');
                }
            })

//            ->editColumn('operator', function (Order $order){
//                return $order->operator ? $order->operator->name : '';
//            })
//            ->editColumn('store', function (Order $order){
//                return $order->store ? $order->store->name : '';
//            })
//            ->editColumn('name_customer', function (Order $order){
//                return $order->client ? $order->client->name : '';
//            })
            ->editColumn('client_phone', function (Order $order){
                return $order->client ? $order->client->allPhones->implode(', ') : '';
            })
//            ->editColumn('status', function (Order $order){
//                return $order->status ? $order->status->status : '';
//            })
            ->editColumn('delivery_time', function (Order $order){
                //return ($order->date_delivery ? $order->date_delivery->format('d.m') : '') .' '.($order->deliveryPeriod ? $order->deliveryPeriod->period_full : '');
                return ($order->deliveryPeriod ? $order->deliveryPeriod->period_full : '');
            })
            ->editColumn('address', function (Order $order){
                return $order->full_address;
            })
            ->editColumn('courier_name', function (Order $order){
//                return $order->courier ? $order->courier->name : '';
                return view('front.logistic.parts.table_select_courier', compact('order'));
            })
            ->editColumn('btn_details', function (Order $order){
                return view('front.logistic.parts.btn_order_group', [ 'id' => $order->id ]);
            })
            ->editColumn('date_delivery', function (Order $order){
                return $order->date_delivery ? $order->date_delivery->format('d.m') : '';
            })

            ->editColumn('sum_price_opt', function (Order $order) {
                return $order->realizations ? $order->realizations->sum('price_opt') : 0;
            })
            ->editColumn('sum_sales', function (Order $order) {
                return $order->realizations ? $order->realizations->sum('price') : 0;
            })
            ->editColumn('sum_profit', function (Order $order) {
                return ($order->realizations ? $order->realizations->sum('price') : 0) -
                            ($order->realizations ? $order->realizations->sum('price_opt') : 0);
            })
            ->editColumn('products', function (Order $order) {
                $products = [];
                foreach ($order->realizations as $realization){
                    if($realization->product){
                        $products[] = $realization->product->product_name;
                    }
                }
                return view('front.logistic.parts.imei_table', ['realizations' => $products]);
            })
            ->editColumn('imei', function (Order $order) {
                return $order->realizations ?
                        view('front.logistic.parts.imei_table', ['realizations' => $order->realizations->pluck('imei')])
                        : "";
            })
            ->editColumn('status_stock', function (Order $order) use (&$stockStatuses){
//                return $order->stockStatus ?
//                    view('front.logistic.parts.other_status_cell', ['status' => $order->stockStatus]) : "";
                return view('front.logistic.parts.table_select_stock_statuses',
                    [
                        'stockStatuses' => $stockStatuses,
                        'order'         => $order
                    ]
                );
            })

            ->editColumn('status_logist', function (Order $order) {
                return $order->logisticStatus ?
                    view('front.logistic.parts.other_status_cell', ['status' => $order->logisticStatus]) : "";
            })
            ->editColumn('checkbox', function (Order $order){
                return '<input name="checked_order" type="checkbox" style="height: 25px;width: 25px">';
            })
            ->rawColumns(['btn_details', 'imei', 'products', 'status_stock', 'status_logist', 'checkbox', 'courier_name'])
            ->setRowClass(function (Order $order) {
                $class = ($order->stockStatus ? ' bg-' . $order->stockStatus->color : '');

                return $class;
            })
            ->setRowAttr([
                'data-order-id' => function (Order $order) {
                    return $order->id;
                }
            ])
            ->withQuery('total_price', function($query) {
                $queryClone = clone $query->getQuery();
                $queryClone->limit = null;
                $queryClone->offset = null;
                $sum =  Realization::query()
                    ->withoutRefusal()
                    ->whereIn('order_id', $queryClone->pluck('orders.id'))
                    ->whereNull('deleted_at')
                    ->where('price_opt', '<>', 0)
                    ->sum('price');
                return $sum;
            })
            ->withQuery('total_price_opt', function($query) {
                $queryClone = clone $query;
                $queryClone->getQuery()->limit = null;
                $queryClone->getQuery()->offset = null;
                $sum = Realization::query()
                    ->withoutRefusal()
                    ->whereIn('order_id', $queryClone->pluck('orders.id'))
                    ->whereNull('deleted_at')
                    ->sum('price_opt');
                return $sum;
            })
            ->order(function ($query) {
                $ordering = request()->get('order');
                $columns = request()->get('columns');
                $isFirstColumnSort = false;

                if(is_array($ordering) && !empty($ordering)){
                    foreach ($ordering as $value){
                        if(isset($columns[(int)$value['column']])&&
                            is_array($columns[(int)$value['column']])&&
                            $columns[(int)$value['column']]["orderable"] == "true"){
                            if($value['column'] == 0){
                                $query->orderBy('orders.updated_at', 'DESC');
                                $isFirstColumnSort = true;
                            }
                            if(self::getNameColumnOnIndex($value['column']) == 'status_stock'){
                                $query->orderBy('other_statuses.ordering', $value['dir'])
                                    ->orderBy('couriers.name', 'DESC');
                            }else{
                                $query->orderBy($columns[(int)$value['column']]["name"], $value['dir']);
                            }

                        }
                    }
                }
                if($isFirstColumnSort){
                    $query->orderBy('delivery_periods.timeFrom', 'ASC');
                }else{
                    $query->orderBy('orders.updated_at', 'DESC')
                        ->orderBy('delivery_periods.timeFrom', 'ASC');
                }
            })
            ;

        if($isDisabledPages){
            $datatable->skipPaging();
        }

        return $datatable->make(true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logistCopyToggle(Request $request)
    {
        $realiz = Realization::findOrFail($request->get('realization_id'));
        $isForcedSendQuick = (bool)$request->get('is_forced_send_quick');
        $message = 'Скопировано';
        $order = $realiz->order;

        if($realiz->is_copy_logist && $order->is_send_quick  && !$isForcedSendQuick){
            return response()->json([
                'is_send_quick' => $order->is_send_quick,
                'order_id'      => $order->id
            ]);
        }

        if(!$order->is_send_quick || $isForcedSendQuick){
            //SendOrderQuickJob::dispatch($order);
            //$message .= '. Заказ отправлен в "Бегунок"!';
        }

        $realiz->is_copy_logist = true;
        $realiz->save();
        event(new RealizationCopyLogistEvent($realiz));
//        if(file_exists(storage_path('app/google/key_table.json'))){
//            $rowGet = str_replace('<td>', '', $request->get('row'));
//            $row = explode('</td>', $rowGet);
//            (new GoogleSheets())->writeRow($row);
//        }

        return response()->json(['type' => 'success', 'message' => $message]);
    }

    public function sendGoogleTables(Order $order)
    {
        $logistOrderData = app(OrderLogistData::class, ['order' => $order]);
        dispatch(new SendLogistGoogleTable($logistOrderData))->onQueue('google-tables');

        return response()->json(['message' => 'Успешно отправлено!']);
    }

    /**
     * @param Order|null $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function onLogistTableUpdate(Order $order = null)
    {
        if($order){
            event(new LogistTableUpdateEvent($order));
        }else{
            event(new LogistTableUpdateEvent());
        }

        return response()->json(['status' => 'send']);
    }

    /**
     * @param Request $request
     * @param DeliveryPeriodsRepository $deliveryPeriodsRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deliveries(Request $request, DeliveryPeriodsRepository $deliveryPeriodsRepository)
    {
        $selectedDate = Carbon::parse($request->get('date')) ?? Carbon::today();
        $periods = $deliveryPeriodsRepository->getDeliveryPeriods($selectedDate);

        return view('front.logistic.delivery_intervals',
                    compact('periods','selectedDate'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function deliveriesForWidget()
    {
        $view = view('front.widgets.delivery_periods_widget')->render();

        return response()->json(['html' => $view]);
    }

    /**
     * @param Request $request
     * @param DeliveryPeriodsRepository $deliveryPeriodsRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function deliveryToggle(Request $request, DeliveryPeriodsRepository $deliveryPeriodsRepository)
    {
        $model = $request->get('model');
        $id = $request->get('id');
        $date = $request->get('date');
        if(!$model || !$id || !$date){
            return response()->json(['message' => 'Произошла ошибка!']);
        }
        $model = app($model);
        $model = $model::find($id);

        if($failDeliveryModel = $model->failDeliveryDate()->whereDate('date', '=', Carbon::parse($date))->first()){
            $failDeliveryModel->stop = !$failDeliveryModel->stop;
            $failDeliveryModel->save();
        }else{
            $model->failDeliveryDate()->save(FailDeliveryDate::create(['date' => $date]));
        }
        $periods = $deliveryPeriodsRepository->getDeliveryPeriods(Carbon::parse($date));

        $html = view('front.logistic.parts.delivery_list',
                        ['periods' => $periods, 'date' => Carbon::parse($date)])->render();

        return response()->json(['message' => 'Успешно обновлено!', 'html' => $html]);
    }
}
