<?php

namespace App\Http\Controllers;

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
        return view('price-upload', ['priceLists' => PriceType::all()]);
    }

    /**
     * @param UploadPrice $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPrice(UploadPrice $request)
    {
        $origName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->store('prices');
        File::create(['name' => $origName, 'path' => $path]);

        return redirect()->route('product.index')->with(['message' => 'Файл успешно загружен']);
    }
    
    
    
}
