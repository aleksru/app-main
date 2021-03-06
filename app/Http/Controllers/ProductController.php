<?php

namespace App\Http\Controllers;

use App\Enums\FileStatusesEnums;
use App\Jobs\ImportPricesJob;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\UploadPrice;
use App\File;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize(Product::class);

        return view('price-upload');
    }

    /**
     * @param UploadPrice $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPrice(UploadPrice $request)
    {
        $this->authorize(Product::class);

        $origName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->store('prices');
        $file = File::create([
            'name'          => $origName,
            'path'          => $path,
            'price_list_id' => $request->get('price_list_id')
        ]);
        ImportPricesJob::dispatch($file->id)->onQueue('files');

        return redirect()->route('product.index')->with(['message' => 'Файл успешно загружен']);
    }


    /**
     * Поиск товара
     *
     * @param Request $request
     * @return json
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $query = strtolower($query);

        $products = Product::query()
            ->whereRaw('LOWER(product_name) like ?', "%{$query}%")
            ->get();

        return response()->json(['products' => $products]);
    }

    /**
     * Создание товара кастомного товара
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $article = Product::where('article', 'LIKE', '%'.Product::PREFIX_CUSTOM_PRODUCT.'%')->orderBy('id', 'desc')->first();
        $article = $article ? ((int)$article->article + 1).Product::PREFIX_CUSTOM_PRODUCT : '1000'.Product::PREFIX_CUSTOM_PRODUCT;
        $product = Product::create([
            'product_name' => $request->get('product_name'),
            'article'      => $article,
            'type'         => $request->get('type')
        ]);

        return response()->json(['product' => $product, 'message' => 'Товар создан и добавлен в заказ!']);
    }

    public function filesDatatable(Request $request)
    {
        return datatables() ->of(
            File::query()
                ->selectRaw('files.*, price_types.name as price_list_name')
                ->leftJoin('price_types', 'files.price_list_id', '=', 'price_types.id'))
            ->editColumn('status', function (File $file) {
                return FileStatusesEnums::getDesc($file->status);
            })
            ->editColumn('created_at', function (File $file) {
                return $file->created_at->format('d.m.Y H:i:s');
            })
            ->make(true);
    }
}
