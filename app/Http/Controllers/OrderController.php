<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;
use App\Order;

class OrderController extends Controller
{
    /**
     * return \Illuminate\Http\Response
     */
    public function index()
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
        $message = 'Успено обновлена';
        $order->update($request->validated());

        return redirect()->route('orders.index');//->with(['errors' => collect([$message])]);
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
                                return $order->status ? '' : view('datatable.actions', [
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
                            ->rawColumns(['actions', 'status', 'products'])
                            ->make(true);
    }
}
