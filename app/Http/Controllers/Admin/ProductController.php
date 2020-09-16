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

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product]);
    }

    public function update(CreateProductRequest $productRequest, Product $product)
    {
        $data = $productRequest->validated();
        if(empty($data['article'])){
            $data['article'] = Product::createCustomArticle();
        }
        $product->update($data);

        return redirect()->route('admin.products.edit', $product->id);
    }

    public function store(CreateProductRequest $productRequest)
    {
        $data = $productRequest->validated();
        if(empty($data['article'])){
            $data['article'] = Product::createCustomArticle();
        }
        Product::create($data);

        return redirect()->route('admin.products.index');
    }


    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(Product::withoutIsActive())
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
            ->editColumn('state', function (Product $product) {
                return view('datatable.toggle', [
                    'check' => $product->isActive(),
                    'id' => $product->id,
                    'route' => route('admin.products.toggle.active'),
                ]);
            })
            ->editColumn('edit', function (Product $product) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('admin.products.edit', $product->id)
                    ],
                ]);
            })
            ->rawColumns(['actions', 'state', 'edit'])
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

    public function toggleActive(Request $request)
    {
        $product = Product::withoutIsActive()->findOrFail($request->get('id'));
        $product->is_active = ! $product->is_active;
        $product->save();

        return response()->json(['message' => "Состояние успешно изменено!"]);
    }


}
