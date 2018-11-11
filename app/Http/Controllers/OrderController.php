<?php

namespace App\Http\Controllers;

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
     * return \Illuminate\Http\Response
     */
    public function index(UpdateOrderRequest $request)
    {
        return view('orders');
    }

    /**
     *
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        //
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param UpdateOrderRequest $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());

        return redirect()->route('orders.index')->with(['success' => 'Успешно обновлена']);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Order::query())
                            ->editColumn('actions', function (Order $order) { 
                                return $order->status ? '' : view('datatable.actions_order', [
                                                                'edit' => [
                                                                    'route' => route('orders.update', $order->id),
                                                                ],
                                                                'delete' => [
                                                                    'id' => $order->id,
                                                                    'name' => $order->store,
                                                                    'route' => route('orders.destroy', $order->id)
                                                                ],
                                                                'orderId' => (string)$order->id,
                                                            ]);         
                            })
                            ->editColumn('status', function (Order $order) { 
                                return view('datatable.status', [
                                                    'status' => $order->user_id,
                                                    'status_code' => $order->status,
                                            ]);         
                            })
                            ->editColumn('products', function (Order $order) {
                                return view('datatable.products', [
                                                    'products' => $order->products,
                                            ]);         
                            })
                            ->editColumn('name_customer', function (Order $order) {
                                if ($order->client){
                                    return view('datatable.customer', [
                                        'route' => route('clients.show', $order->client->id),
                                        'name_customer' => $order->name_customer
                                    ]);
                                }
                            })
                            ->rawColumns(['actions', 'status', 'products', 'name_customer'])
                            ->make(true);
    }
}
