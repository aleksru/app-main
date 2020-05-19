<?php

namespace App\Http\Controllers;


use App\PriceType;
use App\Product;
use Illuminate\Database\Eloquent\Builder;

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
        $query = PriceType::query()
            ->select([
                'products.article',
                'products.product_name',
                'price_type_product.price',
                'price_type_product.price_special',
                'price_type_product.updated_at'
            ])
            ->where('price_types.id', $priceList->id)
            ->join('price_type_product', 'price_types.id', '=', 'price_type_product.price_type_id')
            ->join('products', 'price_type_product.product_id', '=', 'products.id');
        return datatables()->eloquent($query)
                            ->filterColumn('product_name', function (Builder $query, $input){
                                return $query->whereRaw('products.product_name like ?', "%{$input}%");
                            })
                            ->make(true);
    }
}