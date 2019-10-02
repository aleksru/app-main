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
                'operator',
                'products',
                'realizations:order_id,product_id',
                'city')
                ->selectRaw('orders.*, c.phone as phone, c.name as name_customer')
                ->join('clients as c', 'client_id', '=', 'c.id')

        )

            ->filterColumn('phone', function ($query, $keyword) {
                if (preg_match('/[0-9]{4,}/', $keyword)){
                    $this->orderQuery->leftJoin('client_phones', 'orders.client_id', '=', 'client_phones.client_id');
                    return $query->whereRaw('c.phone like ?', "%{$keyword}%")
                                ->OrWhereRaw('client_phones.phone like ?', "%{$keyword}%");
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
            ->filterColumn('operator', function ($query, $keyword) {
                if (preg_match('/[0-9]/', $keyword)){
                    return $query->whereRaw('orders.operator_id = ' . $keyword);
                }
            })
            ->filterColumn('id', function ($query, $keyword) {
                if (preg_match('/[0-9]/', $keyword)){
                    return $query->whereRaw('orders.id = ' . $keyword);
                }
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                if (preg_match('/\d{4}.\d{2}.\d{2}/', $keyword)){
                    $dates = explode(',', $keyword);
                    if(count($dates) === 1){
                        return $query->whereDate('orders.created_at', $dates[0]);
                    }
                    if(count($dates) === 2){
                        return $query->whereBetween('orders.created_at', [$dates[0], $dates[1]]);
                    }
                }
            })
            ->filterColumn('name_customer', function ($query, $keyword) {
                if (preg_match('/[A-Za-zА-Яа-я]{3,}/', $keyword)) {
                    return $query->whereRaw('LOWER(c.name) like ?', "%{$keyword}%");
                }
            })
            ->filterColumn('utm_source', function ($query, $keyword) {
                return $query->where('utm_source', $keyword);
            })
            ->filterColumn('city', function ($query, $keyword) {
                if (preg_match('/[0-9]/', $keyword)){
                    return $query->whereRaw('orders.city_id = ' . $keyword);
                }
            })
            ->editColumn('additional_phones', function (Order $order) {
                return $order->client->allAdditionalPhones;
            })
            ->editColumn('courier', function (Order $order) {
                return $order->courier->name ?? '';
            })
            ->editColumn('operator', function (Order $order) {
                return $order->operator->name ?? '';
            })
            ->editColumn('client_id', function (Order $order) {
                return $order->client->id;
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
                $products = $order->products->pluck('product_name')->toArray();
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
            ->editColumn('button', function (Order $order) {
                return view('front.stock.parts.modal_button', ['orderId' => $order->id]);
            })
            ->editColumn('city', function (Order $order) {
                return $order->city ? $order->city->name : '';
            })
            ->setRowClass(function (Order $order) {
                $class = 'row-link';
                $class = $class . ($order->status ? ' bg-' . $order->status->color : ' bg-success');

                return $class;
            })
            ->rawColumns(['actions', 'status_text', 'products', 'name_customer', 'id', 'button'])
            ->make(true);
    }

}