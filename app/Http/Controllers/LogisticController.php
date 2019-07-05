<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Models\FailDeliveryDate;
use App\Models\Logist;
use App\Models\OrderStatus;
use App\Order;
use App\Repositories\DeliveryPeriodsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

       return Cache::remember('logistics_simple_orders_table_' . $request->get('length'),
                                    Carbon::now()->addSeconds(7), function () use ($statusIds){
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
                'realizations.product')
                    ->where('created_at', '>=', Carbon::now()->subDays(4)->toDateString())
                    ->whereIn('status_id', $statusIds)
                    ->get()
                    ->sortBy(function ($product, $key) {
                        return $product->realizations->min('is_copy_logist');
                    });

                $data = (new Report($orders))->prepareData()->getResultsData();

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
                        'data-orderid' => function ($el) {
                            return $el['product.order'];
                        },
                        'data-productid' => function ($el) {
                            return $el['product.product_id'] ?? '';
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
        $order = Order::findOrFail($request->get('order_id'));
        $productId = $request->get('product_id');
        $realiz = $order->realizations()->where('product_id', $productId)->firstOrFail();
        $realiz->is_copy_logist = true;
        $realiz->save();

        return response()->json(['message' => 'Скопировано']);
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