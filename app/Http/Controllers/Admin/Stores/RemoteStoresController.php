<?php


namespace App\Http\Controllers\Admin\Stores;


use App\Http\Controllers\Controller;
use App\Services\RemoteStores\RemoteStoreUpdatePrices;
use App\Store;

class RemoteStoresController extends Controller
{
    /**
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function runUpdatePrices(Store $store)
    {
        if(!$store->url) {
            return response()->json(['error' => 'Не указан сайт!']);
        }
        try{
            $res = (new RemoteStoreUpdatePrices($store))->process();
        }catch (\Exception $e) {
            return response()->json(['error' => 'Произошла ошибка выполнения.']);
        }

        if($res->getStatusCode() !== 200){
            return response()->json(['error' => 'Произошла ошибка выполнения.']);
        }

        return response()->json(['success' => 'Задание на обновление цен успешно добавлено!']);
    }
}