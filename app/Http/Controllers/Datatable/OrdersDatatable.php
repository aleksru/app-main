<?php

namespace App\Http\Controllers\Datatable;

use App\Models\ClientPhone;
use App\Product;
use App\Order;
use \Illuminate\Database\Eloquent\Builder;

class OrdersDatatable
{
    /**
     * @var Builder
     */
    protected $orderQuery;

    /**
     * OrdersDatatable constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->orderQuery = $order::query();
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function setQuery(Builder $builder)
    {
        $this->orderQuery = $builder;
    }

    /**
     * @return Builder
     */
    public function getOrderQuery(): Builder
    {
        return $this->orderQuery;
    }

    public function datatable()
    {
        return datatables() ->of(
            $this->orderQuery->with(
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
            ->filterColumn('store_text', function ($query, $keyword) {
                if (preg_match('/[0-9]/', $keyword)){
                    return $query->whereRaw('orders.store_id = ' . $keyword);
                }
            })
            ->filterColumn('status_text', function ($query, $keyword) {
                if (preg_match('/[0-9]/', $keyword)){
                    return $query->whereRaw('orders.status_id = ' . $keyword);
                }
            })
            ->filterColumn('courier', function ($query, $keyword) {
                if (preg_match('/[0-9]/', $keyword)){
                    return $query->whereRaw('orders.courier_id = ' . $keyword);
                }
            })
            ->filterColumn('id', function ($query, $keyword) {
                if (preg_match('/[0-9]/', $keyword)){
                    return $query->whereRaw('orders.id = ' . $keyword);
                }
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $keyword)){
                    return $query->whereDate('orders.created_at', $keyword);
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
                return '<a href="'.route('orders.edit', $order->id).'" target="_blank"><h4>'.$order->id.'</h4></a>';
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
                $class = 'row-link';
                $class = $class . ($order->status ? ' label-' . $order->status->color : ' label-success');

                return $class;
            })
            ->rawColumns(['actions', 'status_text', 'products', 'name_customer', 'id'])
            ->make(true);
    }

}