<?php

namespace App\Http\Controllers;

use App\Client;
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
        return view('front.orders.form', [ 'order' => $order->load('client', 'products.supplierInOrder') ]);
    }

    /**
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();
        $data['flag_denial_acc'] = isset($data['flag_denial_acc']) ? $data['flag_denial_acc'] : null;
        $order->update($data);

        return redirect()->route('orders.edit', $order->id)->with(['success' => 'Успешно обновлен!']);
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
     * @return int
     */
    public function updateProductsOrder(Request $request)
    {
        $products = $request->get('products');
        $orderID = $request->get('order');

        $toSync = [];

        foreach ($products as $product) {
            $toSync[$product['id']] = $product['pivot'];
        }

        Order::find($orderID)->products()->sync($toSync);

        return response()->json(['message' => 'Товары успешно обновлены!']);
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Order::with('client', 'status'))
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
