<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Models\StockUser;
use App\Order;
use \Illuminate\Database\Eloquent\Builder;

class StockController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view', StockUser::class);

        return view('front.orders.orders', ['routeDatatable' => route('stock.datatable')]);
    }

    /**
     * @param OrdersDatatable $ordersDatatable
     * @return mixed
     */
    public function datatable(OrdersDatatable $ordersDatatable)
    {
        //$builder = $ordersDatatable->getOrderQuery();
        //$builder->where('orders.id', 111);
        //$ordersDatatable->setQuery($builder);

        return $ordersDatatable->datatable();
    }
}