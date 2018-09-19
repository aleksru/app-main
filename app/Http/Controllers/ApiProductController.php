<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\PriceType;

class ApiProductController extends Controller
{
    /**
     * @return json|pattern Product::take($count)->skip($skip)->get();
     */
    public function api(Request $req)
    {
        $count = 10;
        $skip = 0;
        
        empty($req->get('count')) ?: $count = $req->get('count');
        
        if ($req->get('page') && $req->get('page') > 0) {
            $skip  = $count * $req->get('page');
        }
        $result = collect([]);
        PriceType::where('name', $req->get('pricelist'))->first()
                                                        ->products()
                                                        ->take($count)
                                                        ->skip($skip)
                                                        ->select('article','product_name','price')
                                                        ->get()
                                                        ->each(function ($item, $key) use (&$result){
                                                            $result->prepend([
                                                                'article' => $item->article,
                                                                'product_name' => $item->product_name,
                                                                'price' => $item->price
                                                            ]);
                                                        });
        return $result;
    }
    
}
