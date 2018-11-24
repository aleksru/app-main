<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\UploadPrice;
use App\PriceType;
use App\File;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize(Product::class);

        return view('price-upload', ['priceLists' => PriceType::all()]);
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
        File::create(['name' => $origName, 'path' => $path]);

        return redirect()->route('product.index')->with(['message' => 'Файл успешно загружен']);
    }

    public function updateProductsOrder(Request $request)
    {
        $products = $request->get('products');
        $orderID = $request->get('order');

        $toSync = [];

        foreach ($products as $product) {
            $toSync[$product['id']] = $product['pivot'];
        }

        Order::find($orderID)->products()->sync($toSync);

        return 1;
    }

    /**
     * Поиск товара
     *
     * @param Request $request
     * @return json
     */
    public function search(Request $request)
    {
        return Product::where('product_name', 'LIKE', '%'.$request->get('query').'%')->select('id', 'product_name')->get();
    }
    
    
    
}
