<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\PriceType;

class ApiProductController extends Controller
{
    /**
     * @return json;
     */
    public function api(Request $req)
    {
        $count = 10;
        $skip = 0;
        
        empty($req->get('count')) ?: $count = $req->get('count');
        
        if ($req->get('page') && $req->get('page') > 0) {
            $skip  = $count * $req->get('page');
        }
        $result = PriceType::where('name', $req->get('pricelist'))->first()
                                                        ->products()
                                                        ->take($count)
                                                        ->skip($skip)
                                                        ->select('article','product_name','price', 'price_special')
                                                        ->get();
        return response()->json($result);
    }

    public function priceVersion(Request $req)
    {
        $getPrice = PriceType::where('name', $req->get('pricelist'))->first();

        return $getPrice ? [ $getPrice->name => $getPrice->version ] : abort(404);
    }
    
}
