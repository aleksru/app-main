<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\PriceType;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function version(Request $request)
    {
        $priceList = PriceType::where('name', $request->get('pricelist'))->first();

        if (!$priceList) {
            return response()->json(['error' => "Price list {$request->get('pricelist')} not found"]);
        }

        return response()->json([$priceList->name => $priceList->version]);
    }
}