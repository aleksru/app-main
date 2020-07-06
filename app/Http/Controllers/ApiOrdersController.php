<?php

namespace App\Http\Controllers;

use App\Client;
use App\Services\Order\CreateOrderFromApiHandler;
use App\Store;
use App\Http\Requests\ApiSetOrderRequest;
use App\Order;
use Illuminate\Support\Facades\Log;

class ApiOrdersController extends Controller
{
    /**
     * Создание заказа
     *
     * @param ApiSetOrderRequest $req
     * @return string
     */
    public function api(ApiSetOrderRequest $req)
    {
        $data = $req->validated();
        $data['store_phone'] = $data['store_id'];
        Log::channel('api')->error($data);
        $data['products_text'] = json_decode($req->products, true);
        $client = Client::getOrCreateClientFromPhone($data['phone'], $data['name_customer'] ?? 'Не указано');

        $orderBuilder = Order::getBuilder()
            ->setStoreText($data['store_text']?? '')
            ->setComment($data['comment'] ?? '')
            ->setProductText($data['products_text'] ?? null)
            ->setUtmSource($data['utm_source'] ?? null);

        $handler = new CreateOrderFromApiHandler($client, $orderBuilder, Store::where('phone', $data['store_id'])->first());
        $order = $handler->handle();

        return response()->json(['order' => $order ? $order->id : null], 200);
    }
}