<?php


namespace App\Http\Controllers\Admin\Stores;


use App\Http\Controllers\Controller;
use App\Services\RemoteStores\RemoteStoreState;
use App\Services\RemoteStores\RemoteStoreUpdatePrices;
use App\Store;
use Illuminate\Support\Facades\Log;

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
            Log::error($e);
            return response()->json(['error' => 'Произошла ошибка выполнения.']);
        }

        if($res->getStatusCode() !== 200){
            return response()->json(['error' => 'Произошла ошибка выполнения.']);
        }

        return response()->json(['success' => 'Задание на обновление цен успешно добавлено!']);
    }

    /**
     * @param Store $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStateStore(Store $store)
    {
        if(!$store->url) {
            return response()->json(['error' => 'Не указан сайт!', 406]);
        }
        try{
            $res = (new RemoteStoreState($store))->process();
        }catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Произошла ошибка выполнения.'], 406);
        }
        if($res->getStatusCode() !== 200){
            return response()->json(['error' => 'Произошла ошибка выполнения.', 406]);
        }
        $state = json_decode($res->getBody()->getContents(), true);

        return response()->json([ 'state' => $state['message'] ]);
    }
}