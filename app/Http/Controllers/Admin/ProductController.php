<?php

namespace App\Http\Controllers\Admin;


use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateProductRequest;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.products.form');
    }

    public function store(CreateProductRequest $productRequest)
    {
        $data = $productRequest->validated();
        $article = Product::where('article', 'LIKE', '%'.Product::PREFIX_CUSTOM_PRODUCT.'%')->orderBy('id', 'desc')->first();
        $article = $article ? ((int)$article->article + 1).Product::PREFIX_CUSTOM_PRODUCT : '1000'.Product::PREFIX_CUSTOM_PRODUCT;
        $data['article'] = $article;
        Product::create($data);

        return redirect()->route('admin.products.index');
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
            ->editColumn('category', function (Product $product) {
                return $product->getTextCategory();
            })
            ->editColumn('actions', function (Product $product) {
                return view('admin.products.parts.btn_group_actions', [
                    'route_toggle' => route('admin.products.toggle.set-type', $product->id),
                    'route_category' => route('admin.products.toggle.category', $product->id),
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

    public function toggleSetCategory(Request $request, Product $product)
    {
        if($request->get('type')) {
            $product->category = $request->get('type');
            $product->save();
        }
        $type = $product->getTextCategory();

        return response()->json(['message' => "Категория изменена {$type}!"]);
    }
}