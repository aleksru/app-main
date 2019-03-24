<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Requests\CommentLogistRequst;
use App\Models\OtherStatus;
use App\Models\Realization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;
use App\Order;

class OrderController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view', Order::class);

        return view('front.orders.orders');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('update', Order::class);

        return view('front.orders.create');
    }

    /**
     * @param UpdateOrderRequest $updateOrderRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UpdateOrderRequest $updateOrderRequest)
    {
        $this->authorize('update', Order::class);
        Order::create($updateOrderRequest->validated());

        return redirect()->route('orders.edit', Order::create($updateOrderRequest->validated())->id)->with(['success' => 'Заказ успешно создан!']);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Order $order)
    {
        //return view('front.orders.form', [ 'order' => $order ]);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Order $order)
    {
        $this->authorize('show', $order, Order::class);
        $operator = $order->operator ? $order->operator : (Auth()->user()->isOperator() ? Auth()->user()->account : null);

        $subStatuses = OtherStatus::typeSubStatuses()->get();
        $stockStatuses = OtherStatus::typeStockStatuses()->get();
        $logisticStatuses = OtherStatus::typeLogisticStatuses()->get();

        return view('front.orders.form', [
            'order' => $order->load('client', 'realizations.product:id,product_name', 'realizations.supplier'),
            'operator' => $operator,
            'subStatuses' => $subStatuses,
            'stockStatuses' => $stockStatuses,
            'logisticStatuses' => $logisticStatuses
        ]);
    }

    /**
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $this->authorize('update', Order::class);
        $data = $request->validated();
        isset($data['communication_time']) ? $data['communication_time'] = Carbon::now()->addMinutes((integer)($data['communication_time'] * 60)) : null;
        $order->update($data);

        if($order->status && stripos($order->status->status, 'отказ') === false || !$order->status) {
            $order->denial_reason_id = null;
            $order->save();
        }

        return redirect()->route('orders.edit', $order->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * Динамическое изменение статуса заказа
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', Order::class);
        $statusId = Order::statusFinallyId();

        $order->update(['status_id' => $statusId]);

        return response()->json(['message' => 'Заказ переведен в статус "Завершен"']);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Обновление товаров в заказе
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProductsOrder(Request $request, Order $order)
    {
        $products = $request->get('products');
        $realizations = [];
        $realizationsId = [];

        foreach ($products as $product) {
            $realization = Realization::updateOrCreate(['id' => $product['id']], $product);
            $realizations[] = $realization;
            $realizationsId[] = $realization->id;
        }

        $allRealizations = $order->realizations()->pluck('id')->toArray();
        $arrDiff = array_diff($allRealizations, $realizationsId);

        //save realizations
        $order->realizations()->saveMany($realizations);

        Realization::destroy($arrDiff);

        return response()->json(['message' => 'Товары успешно обновлены!', 'products' => $order->realizations()->with('product:id,product_name', 'supplier')->get()]);
    }

    /**
     * Добавление комментария логиста
     *
     * @param CommentLogistRequst $commentLogistRequst
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function commentLogist(CommentLogistRequst $commentLogistRequst, Order $order)
    {
        $this->authorize('commentLogist', Order::class);
        $order->update($commentLogistRequst->validated());

        return redirect()->route('orders.edit', $order->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @return json
     */
    public function datatable(OrdersDatatable $ordersDatatable)
    {
       return $ordersDatatable->datatable();
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderWithRealizations(Request $request, Order $order)
    {
        return response()->json($order->load('realizations.product', 'deliveryType', 'realizations.supplier'));
    }
}
