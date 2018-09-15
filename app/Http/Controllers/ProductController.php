<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadPrice;
use Illuminate\Support\Facades\Storage;
use App\File;

class ProductController extends Controller
{
    public function index()
    {
        //debug(Service\ExelService::excelToArray(storage_path('app/'.File::first()->path)));
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
