<?php

namespace App\Http\Controllers;

use App\Client;
use App\Models\ClientPhone;
use App\Models\Realization;
use App\Product;
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
        $operator = $order->operator ? $order->operator : (Auth()->user()->isOperator() ? Auth()->user()->account : null);

        return view('front.orders.form', [
            'order' => $order->load('client', 'realizations.product:id,product_name', 'realizations.supplier'),
            'operator' => $operator
        ]);
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
        return datatables() ->of(
            Order::with(
                'status',
                'store',
                'client',
                'client.additionalPhones',
                'courier',
                'realizations:order_id,product_id')
                                                    ->selectRaw('orders.*')
                                                    ->selectRaw('c.phone as phone')
                                                    ->selectRaw('c.name as name_customer')
                                                    ->selectRaw('o_status.status as status_text')
                                                    ->join('clients as c', 'client_id', '=', 'c.id')
                                                    ->leftJoin('order_statuses as o_status', 'status_id', '=', 'o_status.id'))

            ->filterColumn('phone', function ($query, $keyword) {
                if (preg_match('/[0-9]{4}/', $keyword)){
                    return $query->whereRaw('c.phone like ?', "%{$keyword}%");
                }
            })
            ->filterColumn('additional_phones', function ($query, $keyword) {
                if (preg_match('/[0-9]{4}/', $keyword)) {
                    $clientPhones = ClientPhone::where('phone', 'LIKE', "%{$keyword}%")->pluck('client_id');

                    return $query->whereIn('orders.client_id', $clientPhones);
                }
            })
            ->filterColumn('name_customer', function ($query, $keyword) {
                if (preg_match('/[A-Za-z]{3}/', $keyword)) {
                    return $query->whereRaw('LOWER(c.name) like ?', "{$keyword}%");
                }
            })
            ->editColumn('additional_phones', function (Order $order) {
                return $order->client->allAdditionalPhones;
            })
            ->editColumn('courier', function (Order $order) {
                return $order->courier->name ?? '';
            })
            ->editColumn('id', function (Order $order) {
                return '<a href="'.route('orders.show', $order->id).'" target="_blank"><h4>'.$order->id.'</h4></a>';
            })
            ->editColumn('actions', function (Order $order) {
                return view('datatable.actions_order', [
                    'order' => $order,
                ]);
            })
            ->editColumn('status_text', function (Order $order) {
                return view('datatable.status', [
                                    'status' => $order->status
                ]);
            })
            ->editColumn('products', function (Order $order) {
                if(!$order->realizations->isEmpty()){
                    $products = Product::find($order->realizations->pluck('product_id'))->pluck('product_name')->toArray();
                }
                return !empty($products) ? implode(', ', $products) :
                    view('datatable.products', [
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
            ->setRowClass(function (Order $order) {
                return $order->status ? 'label-'.$order->status->color : 'label-success';
            })
            ->rawColumns(['actions', 'status_text', 'products', 'name_customer', 'id'])
            ->make(true);
    }
}
