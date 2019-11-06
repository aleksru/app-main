<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\PriceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function products(Request $request)
    {
        Log::channel('api_prices')->error(["App-Http-Controllers-Api-ProductController.", $request->all()]);
        $count = $request->get('count') ?? 500;
        $skip = 0;
        $priceList = PriceType::where('name', $request->get('pricelist'))->first();

        if (!$priceList) {
            return response()->json(['error' => "Price list {$request->get('pricelist')} not found"]);
        }

        if ($request->get('page') && $request->get('page') > 0) {
            $skip = $count * ($request->get('page') - 1);
        }

        $result = $priceList->products()
                            ->take($count)
                            ->skip($skip)
                            ->select('article', 'product_name', 'price', 'price_special')
                            ->get();

        return response()->json($result);
    }
}