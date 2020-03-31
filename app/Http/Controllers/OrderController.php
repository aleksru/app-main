<?php

namespace App\Http\Controllers;

use App\Events\OrderUpdateRealizationsEvent;
use App\Events\UpdatedOrderEvent;
use App\Events\UpdateRealizationsConfirmedOrderEvent;
use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Http\Requests\CommentLogistRequst;
use App\Http\Requests\OrderLogisticRequest;
use App\Http\Requests\RealizationLogisticRequest;
use App\Models\OrderStatus;
use App\Models\OtherStatus;
use App\Models\Realization;
use App\Notifications\ClientCallBack;
use App\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        $authUser = Auth()->user();

        if ( ! $order->operator && $authUser->isOperator() ){
            $order->operator()->associate($authUser->account);
            $order->save();
        }

        if ($authUser->isOperator() && $authUser->account && $authUser->account->id !==  $order->operator->id){
            $message = 'Заказ обрабатывает оператор ' . ($order->operator->name ?? '');
            session()->flash('warning', $message);
        }
        $subStatuses = OtherStatus::typeSubStatuses()->get();
        $stockStatuses = OtherStatus::typeStockStatuses()->get();
        $logisticStatuses = OtherStatus::typeLogisticStatuses()->get();

        return view('front.orders.form', [
                'order' => $order->load(['client.orders' => function($query){
                    $query->orderBy('id', 'DESC');
                },
                'realizations.product',
                'realizations.supplier'
            ]),
            'operator' => $order->operator,
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
        isset ($data['communication_time']) ?
            $data['communication_time'] = Carbon::parse($data['communication_time']) : null;

        $order->update($data);

        if($order->status && stripos($order->status->status, 'отказ') === false || !$order->status) {
            $order->denial_reason_id = null;
            $order->save();
        }

        return response()->json(['success' => 'Форма заказа успешно обновлена!']);
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
            $modelProduct = Product::find($product['product']['id']);
            if($modelProduct && $product['product_type']){
                $modelProduct->update(['type' => $product['product_type']]);
            }
            //$product['product_type'] = $product['product']['type'];
            $realization = Realization::updateOrCreate(['id' => $product['id']], $product);
            $realizations[] = $realization;
            $realizationsId[] = $realization->id;
        }

        $allRealizations = $order->realizations()->pluck('id')->toArray();
        $arrDiff = array_diff($allRealizations, $realizationsId);

        //save realizations
        $order->realizations()->saveMany($realizations);

        Realization::destroy($arrDiff);
        event(new OrderUpdateRealizationsEvent($order));

        return response()->json(['success' => 'Товары успешно обновлены!', 'products' => $order->realizations()->with('product', 'supplier')->get()]);
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

        return response()->json(['success' => 'Комментарий логиста успешно обновлен!']);
    }

    /**
     * @return json
     */
    public function datatable(OrdersDatatable $ordersDatatable)
    {
        $isOperator = Cache::remember('is_operator_user_id_' . Auth::user()->id, Carbon::now()->addHours(2), function (){
            return Auth::user()->isOperator();
        });
        $ordersDatatable->setQuery(
            $ordersDatatable->getOrderQuery()
                            ->whereNotNull($isOperator ? 'orders.status_id' : 'orders.id')
                            ->orderBy('updated_at', 'DESC')
                            ->orderBy('id', 'DESC'));

        return $ordersDatatable->datatable();
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderWithRealizations(Request $request, Order $order)
    {
        return response()->json($order->load([
            'realizations' => function($query){
                $query->orderBy('product_type');
            },
            'realizations.product',
            'deliveryType',
            'realizations.supplier']),
        200, [], JSON_NUMERIC_CHECK);
    }

    /**
     * @param Request $request
     * @param Order $order
     * @param User $user
     */
    public function unLoad(Request $request, Order $order, User $user) : void
    {
        $order->views()->detach($user);
    }

    /**
     * @param Request $request
     * @param Order $order
     * @param User $user
     */
    public function onLoad(Request $request, Order $order, User $user) : void
    {
        $order->views()->syncWithoutDetaching($user);
        $statusIdNew = Cache::remember('ID_ORDER_STATUS_NEW', Carbon::now()->addHours(4), function (){
            return OrderStatus::getIdStatusNew();
        });
        if($statusIdNew == $order->status_id) {
            event(new UpdatedOrderEvent($order->load('status', 'client', 'operator', 'store')));
        }
    }

    /**
     * @param Order $order
     * @param OrderLogisticRequest $orderLogisticRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderLogisticUpdate(Order $order, OrderLogisticRequest $orderLogisticRequest)
    {
        $order->update($orderLogisticRequest->validated());

        return response()->json($order);
    }

    /**
     * @param Realization $realization
     * @param RealizationLogisticRequest $realizationLogisticRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function realizationLogisticUpdate(Realization $realization, RealizationLogisticRequest $realizationLogisticRequest)
    {
        $realization->update($realizationLogisticRequest->validated());

        return response()->json($realization->load('product'));
    }
}
