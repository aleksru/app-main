<?php

namespace App\Http\Controllers;


use App\Events\LogistTableUpdateEvent;
use App\Events\RealizationCopyLogistEvent;
use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Jobs\SendLogistGoogleTable;
use App\Jobs\SendOrderQuickJob;
use App\Models\Courier;
use App\Models\FailDeliveryDate;
use App\Models\Logist;
use App\Models\OrderStatus;
use App\Models\Realization;
use App\Order;
use App\Repositories\DeliveryPeriodsRepository;
use App\Services\Google\Sheets\Data\OrderLogistData;
use App\Services\Google\Sheets\GoogleSheets;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LogisticController extends Controller
{
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

        return view('front.logistic.simple_orders', [
            'routeDatatable' => route('logistics.simple.orders.datatable'),
            'couriersSelect'  => $couriersSelect
        ]);
    }

    /**
     * @return mixed
     */
    public function simpleOrdersDatatable(Request $request)
    {
        $statusIds = Cache::remember('logistics.status.ids', Carbon::now()->addHours(4), function () {
            return OrderStatus::getIdsStatusesForLogistic();
        });
        $user = Auth::user();
        $accessCitiesByLogistIds = Cache::remember('ACCESS_CITIES_LOGIST_' . $user->id, Carbon::now()->addHours(4), function () use ($user){
            return ($user->isLogist() && $user->account) ?
                $user->account->cities->pluck('id') : collect();
        });

        $orders = Order::with(
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
            'realizations'
        )->selectRaw('orders.*, delivery_periods.timeFrom')
            ->leftJoin('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id')
            ->where('orders.date_delivery', '>=', Carbon::now()->subDays(7)->toDateString())
            ->whereIn('orders.status_id', $statusIds)
            ->orderBy('orders.date_delivery', 'ASC')
            ->orderBy('delivery_periods.timeFrom', 'ASC');

        if( ! $accessCitiesByLogistIds->isEmpty() ) {
            $orders->whereIn('orders.city_id', $accessCitiesByLogistIds);
        }
        return datatables()->of($orders)
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
                    $orders->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id');
                    return $query->where('couriers.id', $keyword);
                }else{
                    return $query->whereNull('orders.courier_id');
                }

            })
            ->filterColumn('address', function ($query, $keyword){
                return $query->whereRaw('LOWER(orders.address_street) like ?', "%{$keyword}%");
            })
            ->filterColumn('date_delivery', function ($query, $keyword) {
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
                return $order->courier ? $order->courier->name : '';
            })
//            ->editColumn('courier_payment', function (Order $order){
//                return $order->courier_payment;
//            })
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
                //return $order->products ? $order->products->pluck('product_name')->implode(', ') : '';
                return $order->products ?
                    view('front.logistic.parts.imei_table', ['realizations' => $order->products->pluck('product_name')]) :
                    '';
            })
            ->editColumn('imei', function (Order $order) {
                return $order->realizations ?
                        view('front.logistic.parts.imei_table', ['realizations' => $order->realizations->pluck('imei')])
                        : "";
            })
            ->rawColumns(['btn_details', 'imei', 'products'])
            ->setRowClass(function (Order $order) {
                $class = ($order->logisticStatus ? ' bg-' . $order->logisticStatus->color : '');

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
                return Realization::query()
                    ->whereIn('order_id', $queryClone->pluck('orders.id'))
                    ->whereNull('deleted_at')
                    ->where('price_opt', '<>', 0)
                    ->sum('price');
            })
            ->withQuery('total_price_opt', function($query) {
                $queryClone = clone $query;
                $queryClone->getQuery()->limit = null;
                $queryClone->getQuery()->offset = null;
                return Realization::query()
                    ->whereIn('order_id', $queryClone->pluck('orders.id'))
                    ->whereNull('deleted_at')
                    ->sum('price_opt');
            })
            ->make(true);
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
            SendOrderQuickJob::dispatch($order);
            $message .= '. Заказ отправлен в "Бегунок"!';
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function onLogistTableUpdate()
    {
        event(new LogistTableUpdateEvent());

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