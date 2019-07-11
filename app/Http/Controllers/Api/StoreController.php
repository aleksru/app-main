<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPriceList(Request $request)
    {
        $phoneNumber = $request->get('phone_number');
        $storeUrl = $request->get('store_url');
        if(!$phoneNumber && !$storeUrl) {
            return response()->json(['error' => 'Store info not found'], 400);
        }
        if($phoneNumber) {
            $store = Store::getStoreByPhoneNumber($phoneNumber);
        }
        if($storeUrl && empty($store)) {
            $store = Store::getStoreByUrl($storeUrl);
        }
        if(empty($store)) {
            return response()->json(['error' => 'Store not found'], 400);
        }

        return response()->json([
            'price-list' => $store->priceType->name ?? null,
            'version' => $store->priceType->version ?? null 
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function onlineStore(Request $request)
    {
        $phoneNumber = $request->get('phone_number');
        $storeUrl = $request->get('store_url');
        if(!$phoneNumber && !$storeUrl) {
            return response()->json(['error' => 'Store info not found'], 400);
        }

        $store = $this->getStoreForRequest($request);
        if(!$store){
            return response()->json(['error' => 'Store not found'], 400);
        }
        $store->active_at = Carbon::now();
        $store->save();

        return response()->json(['store_id' => $store->id], 200);
    }

    /**
     * @param Request $request
     * @return mixed|null
     */
    private function getStoreForRequest(Request $request)
    {
        $phoneNumber = $request->get('phone_number');
        $storeUrl = $request->get('store_url');

        if($phoneNumber) {
            $store =  Store::getStoreByPhoneNumber($phoneNumber);
        }
        if($storeUrl && empty($store)) {
            $store =  Store::getStoreByUrl($storeUrl);
        }

        return $store ?? null;
    }
}