<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadPrice;
use App\File;

class ProductController extends Controller
{
    public function index()
    {
        debug(\App\PriceType::where('name', 'moscow')->first()
                                                     ->products()
                                                     ->take(5)
                                                     ->skip(5)
                                                     ->select('article','product_name','price')
                                                     ->get()
                                                     ->each(function ($item, $key) {
                                                         debug($item);
                                                     })
                                                     ->toArray());
        return view('price-upload');
    }
    
    public function uploadPrice(UploadPrice $request)
    {
        $origName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->store('prices');
        File::create(['name' => $origName, 'path' => $path]);

        return redirect()->route('product.index')->with(['message' => 'Файл успешно загружен']);
    }
    
    
    
}
