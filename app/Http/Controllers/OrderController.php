<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;
use App\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//       Order::first()->update([
//           'products' => [['art' => 'qwe', 'ssd' => 'sdasda']]
//           ]);
      // debug(Order::first()->products); 
        return view('orders');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $message = 'Успено обновлена';
        $order->update($request->validated());

        return redirect()->route('orders.index');//->with(['errors' => collect([$message])]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function datatable()
    {
        //return datatables()->of(Order::all())->make(true);
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
