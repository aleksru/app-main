<?php

namespace App\Http\Controllers\Admin;


use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.products.index');
    }


    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Product::query())
            ->editColumn('type', function (Product $product) {
                return $product->getTextType();
            })
            ->editColumn('actions', function (Product $product) {
                return view('admin.products.parts.toggle', [
                    'route' => route('admin.products.toggle.set-type', $product->id),
                    'id' => $product->id
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Изменение типа
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleSetType(Request $request, Product $product)
    {
        if($request->get('type')) {
            $product->type = $request->get('type');
            $product->save();
        }
        $type = $product->getTextType();

        return response()->json(['message' => "Тип успешно изменен на {$type}!"]);
    }
}