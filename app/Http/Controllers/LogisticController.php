<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Report;
use App\Models\Logist;
use App\Models\OrderStatus;
use App\Order;
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
            'realizations')->where('created_at', '>=',Carbon::today()->toDateString())->whereIn('status_id', $statusIds)->get();

        $data = (new Report($orders))->prepareData()->getResultsData();

        return datatables()->of($data['product'])
            ->editColumn('product.real_denied', '')
            ->editColumn('product.comment_logist', '')
            ->setRowClass(function ($el) {
                if($productId = $el['product.product_id'] ?? false) {
                    $order = Order::find($el['product.order']);
                    $realiz = $order->realizations()->where('product_id', $productId)->first();

                    return $realiz && $realiz->is_copy_logist ? 'alert-success' : 'alert-danger';
                }

                return 'alert-danger';
            })
            ->setRowAttr([
                'data-orderid' => function($el) {
                    return $el['product.order'];
                },
                'data-productid' => function($el) {
                    return $el['product.product_id'] ?? '';
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
        $order = Order::findOrFail($request->get('order_id'));
        $productId = $request->get('product_id');
        $realiz = $order->realizations()->where('product_id', $productId)->firstOrFail();
        $realiz->is_copy_logist = true;
        $realiz->save();

        return response()->json(['message' => 'Скопировано']);
    }
}