<?php

namespace App\Http\Controllers;


use App\PriceType;
use App\Product;

class PriceListController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize(Product::class);

        return view('front.price-lists.index');
    }

    /**
     * @param PriceType $priceList
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(PriceType $priceList)
    {
        $this->authorize('index',Product::class);

        return view('front.price-lists.show', ['priceType' => $priceList]);
    }

    /**
     * @return mixed
     */
    public function datatable()
    {
        return datatables() ->of(PriceType::query())
                            ->editColumn('actions', function (PriceType $priceType) {
                                return '<a href="'.route('price-lists.show', $priceType->id).'">
                                            <i class="fa fa fa-sign-in btn btn-xs btn-success"></i>
                                        </a>';
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * @param PriceType $priceList
     * @return mixed
     */
    public function showDatatable(PriceType $priceList)
    {
        return datatables()->of($priceList->products())
                            ->make(true);
    }
}