<?php

namespace App\Http\Controllers;

use App\Client;
use App\Models\Realization;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;
use App\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class);
        $this->middleware('can:view,App\Order')->only('index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.orders.orders');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('front.orders.create');
    }

    /**
     * @param UpdateOrderRequest $updateOrderRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UpdateOrderRequest $updateOrderRequest)
    {
        Order::create($updateOrderRequest->validated());

        return redirect()->route('orders.edit', Order::create($updateOrderRequest->validated())->id)->with(['success' => 'Заказ успешно создан!']);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Order $order)
    {
        return view('front.orders.form', [ 'order' => $order ]);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Order $order)
    {
        return view('front.orders.form', [ 'order' => $order->load('client', 'realizations.product:id,product_name', 'realizations.supplier') ]);
    }

    /**
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();
        debug($data);
        $data['flag_denial_acc'] = isset($data['flag_denial_acc']) ? $data['flag_denial_acc'] : null;
        $order->update($data);

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
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Order::with('status', 'store', 'client')->join('clients as c', 'client_id', '=', 'c.id')
                                                                                  ->selectRaw('orders.*, c.phone as phone')
                                                                                  ->selectRaw('orders.*, c.name as name_customer'))
                            ->filterColumn('phone', function ($query, $keyword) {
                                return $query->whereRaw('LOWER(c.phone) like ?', "{$keyword}%");
                            })
                            ->filterColumn('name_customer', function ($query, $keyword) {
                                return $query->whereRaw('LOWER(c.name) like ?', "%{$keyword}%");
                            })
                            ->editColumn('actions', function (Order $order) { 
                                return view('datatable.actions_order', [
                                    'order' => $order,
                                ]);
                            })
                            ->editColumn('status', function (Order $order) { 
                                return view('datatable.status', [
                                                    'status' => $order->status
                                            ]);         
                            })
                            ->editColumn('products', function (Order $order) {
                                return view('datatable.products', [
                                                    'products' => $order->products_text ?? [],
                                            ]);         
                            })
                            ->editColumn('name_customer', function (Order $order) {
                                if ($order->client){
                                    return view('datatable.customer', [
                                        'route' => route('clients.show', $order->client->id),
                                        'name_customer' => $order->client->name ?? 'Не указано'
                                    ]);
                                }
                            })

                            ->editColumn('phone', function (Order $order) {
                                return $order->client->phone ?? '';

                            })

                            ->editColumn('store_text', function (Order $order) {
                                return $order->store->name ?? $order->store_text;

                            })
                            ->rawColumns(['actions', 'status', 'products', 'name_customer'])
                            ->make(true);
    }
}
