<?php


namespace App\Http\Controllers;


use App\Http\Controllers\Datatable\OrdersDatatable;

class MarketingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function utmTags()
    {
        return view('front.marketing.utm');
    }

    /**
     * @param OrdersDatatable $ordersDatatable
     * @return mixed
     */
    public function datatable(OrdersDatatable $ordersDatatable)
    {
        $ordersDatatable->setQuery(
            $ordersDatatable->getOrderQuery()
                ->orderBy('updated_at', 'DESC')
                ->orderBy('id', 'DESC'));

        return $ordersDatatable->datatable();
    }
}