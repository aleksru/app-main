<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Models\FailDeliveryDate;
use App\Models\Logist;
use App\Models\OrderStatus;
use App\Models\Realization;
use App\Order;
use App\Repositories\DeliveryPeriodsRepository;
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
        $columns = $request->get('columns');
        $statusIds = Cache::remember('logistics.status.ids', Carbon::now()->addHours(4), function () {
            return OrderStatus::getIdsStatusesForLogistic();
        });
        $accessCitiesByLogistIds = (Auth::user()->isLogist() && Auth::user()->account) ?
                                        Auth::user()->account->cities->pluck('id') : collect();
        $cacheKey =  $request->get('length') . "_" .
                        $request->get('start') . '_' .
                            implode('_', $accessCitiesByLogistIds->toArray());
        if($columns[4]['search']['value']) {
            $cacheKey = $cacheKey . '_search_' . $columns[4]['search']['value'];
        }

       return Cache::remember('logistics_simple_orders_table_' . $cacheKey,
                                    Carbon::now()->addSeconds(5), function () use ($statusIds, $accessCitiesByLogistIds){
            $orders = Order::with(
                'status',
                'store',
                'client',
                'client.additionalPhones',
                'courier',
                'metro',
                'deliveryPeriod',
                'deliveryType',
                'operator',
                'realizations.product'
            )->where('updated_at', '>=', Carbon::now()->subDays(4)->toDateString())
            ->whereIn('status_id', $statusIds);

            if( ! $accessCitiesByLogistIds->isEmpty() ) {
                $orders->whereIn('city_id', $accessCitiesByLogistIds);
            }

            $data = (new Report($orders->get()))->prepareData()->getResultsData();

            usort($data['product'], function ($a, $b){
                if ( ! $a['product.is_copy_logist'] && ! $b['product.is_copy_logist'] ) {
                    return $a['product.order'] <=> $b['product.order'];
                }

                return ((int)$a['product.is_copy_logist'] <=> (int)$b['product.is_copy_logist']);
            });

            return datatables()->of($data['product'])
                ->editColumn('product.nodata', '-')
                ->editColumn('product.real_denied', '')
                ->editColumn('product.comment_logist', '')
                ->editColumn('product.price_opt', function ($value){
                    return (int)$value['product.price_opt'];
                })
                ->editColumn('product.price', function ($value){
                    return (int)$value['product.price'];
                })
                ->editColumn('product.courier_payment', function ($value){
                    return (int)$value['product.courier_payment'];
                })
                ->editColumn('product.profit', function ($value){
                    return (int)$value['product.profit'];
                })
                ->setRowClass(function ($el){
                    return $el['product.is_copy_logist'] ? 'alert-success' : 'alert-danger';
                })
                ->setRowAttr([
                    'data-realizationid' => function ($el) {
                        return $el['product.realization_id'];
                    }
                ])
                ->toJson();
        });
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