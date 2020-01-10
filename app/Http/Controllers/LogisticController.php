<?php

namespace App\Http\Controllers;


use App\Events\RealizationCopyLogistEvent;
use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Models\FailDeliveryDate;
use App\Models\Logist;
use App\Models\OrderStatus;
use App\Models\Realization;
use App\Order;
use App\Repositories\DeliveryPeriodsRepository;
use App\Services\Google\Sheets\GoogleSheets;
use Carbon\Carbon;
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

        return view('front.logistic.simple_orders', ['routeDatatable' => route('logistics.simple.orders.datatable')]);
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
            'client',
            'client.additionalPhones',
            'courier',
            'metro',
            'deliveryPeriod',
            'deliveryType',
            'operator'
        )->selectRaw('orders.*, realizations.id as realization_id,  realizations.price, realizations.quantity, realizations.imei, realizations.price_opt, 
                        realizations.supplier_id, realizations.courier_payment, realizations.is_copy_logist,
                        client.phone as client_phone, client.name as name_customer, suppliers.name as supplier, products.product_name, 
                        (realizations.price - IFNULL(realizations.price_opt, 0)) as profit')
            ->join('clients as client', 'client_id', '=', 'client.id')
            ->join('realizations', 'orders.id', '=', 'realizations.order_id')
            ->join('products', 'product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'supplier_id', '=', 'suppliers.id')
            ->where('orders.updated_at', '>=', Carbon::now()->subDays(4)->toDateString())
            ->whereNull('realizations.deleted_at')
            ->whereIn('orders.status_id', $statusIds)
            ->orderBy('is_copy_logist')
            ->orderBy('orders.id', 'DESC');

        if( ! $accessCitiesByLogistIds->isEmpty() ) {
            $orders->whereIn('orders.city_id', $accessCitiesByLogistIds);
        }
        return datatables()->of($orders)
            ->filterColumn('client_phone', function ($query, $keyword) {
                if (preg_match('/[0-9]{4,}/', $keyword)){
                    return $query->whereRaw('client.phone like ?', "%{$keyword}%");
                }
            })
            ->filterColumn('name_customer', function ($query, $keyword) {
                if (preg_match('/[A-Za-zА-Яа-я]{3,}/', $keyword)) {
                    return $query->whereRaw('LOWER(client.name) like ?', "%{$keyword}%");
                }
            })
            ->editColumn('nodata', '-')
            ->editColumn('real_denied', '')
            ->editColumn('comment_logist', '')
            ->editColumn('operator', function (Order $order){
                return $order->operator ? $order->operator->name : '';
            })
            ->editColumn('store', function (Order $order){
                return $order->store ? $order->store->name : '';
            })
            ->editColumn('client_phone', function (Order $order){
                return $order->client ? $order->client->allPhones->implode(', ') : '';
            })
            ->editColumn('status', function (Order $order){
                return $order->status ? $order->status->status : '';
            })
            ->editColumn('delivery_time', function (Order $order){
                return ($order->date_delivery ? $order->date_delivery->format('d.m') : '') .' '.($order->deliveryPeriod ? $order->deliveryPeriod->period : '');
            })
            ->editColumn('address', function (Order $order){
                return ($order->metro ? 'м.' . $order->metro->name . ', ' : '') . $order->fullAddress;
            })
            ->editColumn('courier_name', function (Order $order){
                return $order->courier ? $order->courier->name : '';
            })
            ->editColumn('price_opt', function ($value){
                return (int)$value['price_opt'];
            })
            ->editColumn('price', function ($value){
                return (int)$value['price'];
            })
            ->editColumn('courier_payment', function ($value){
                return (int)$value['courier_payment'];
            })
            ->setRowClass(function ($el){
                return $el['is_copy_logist'] ? 'alert-success' : 'alert-danger';
            })
            ->setRowAttr([
                'data-realizationid' => function (Order $order) {
                    return $order->realization_id;
                }
            ])
            ->make(true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logistCopyToggle(Request $request)
    {
        $realiz = Realization::findOrFail($request->get('realization_id'));

        if($realiz->is_copy_logist){
            return response()->json(['type' => 'error', 'message' => 'Уже скопировано']);
        }
        $realiz->is_copy_logist = true;
        $realiz->save();
        event(new RealizationCopyLogistEvent($realiz));
        if(file_exists(storage_path('app/google/key_table.json'))){
            $rowGet = str_replace('<td>', '', $request->get('row'));
            $row = explode('</td>', $rowGet);
            (new GoogleSheets())->writeRow($row);
        }

        return response()->json(['type' => 'success', 'message' => 'Скопировано']);
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