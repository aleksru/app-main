<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Models\Logist;
use App\Models\OrderStatus;
use App\Order;
use Carbon\Carbon;
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
    public function simpleOrdersDatatable()
    {
        $statusIds = Cache::remember('logistics.status.ids', Carbon::now()->addHours(4), function (){
            return OrderStatus::getIdsStatusesForLogistic();
        });

        $orders = Order::with(
            'status',
            'store',
            'client',
            'client.additionalPhones',
            'courier',
            'metro',
            'deliveryPeriod',
            'operator',
            'realizations:order_id,product_id')->where('created_at', '>=',Carbon::today()->toDateString())->whereIn('status_id', $statusIds)->get();

        $data = (new Report($orders))->prepareData()->getResultsData();

        return datatables()->of($data['product'])
            ->editColumn('product.real_denied', '')
            ->editColumn('product.comment_logist', '')
            ->make(true);
    }
}