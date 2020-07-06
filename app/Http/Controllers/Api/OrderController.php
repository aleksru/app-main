<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Order;
use App\Services\Order\CreateOrderFromApiHandler;
use App\Store;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * @param CreateOrderRequest $createOrderRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateOrderRequest $createOrderRequest)
    {
        $data = $createOrderRequest->validated();
        Log::channel('api')->error($data);
        $data['products_text'] = json_decode($createOrderRequest->products, true);
        $client = Client::getOrCreateClientFromPhone($data['phone'], $data['name_customer'] ?? 'Не указано');

        $orderBuilder = Order::getBuilder()
            ->setStoreText($data['store_text'])
            ->setComment($data['comment'] ?? '')
            ->setProductText($data['products_text'] ?? null)
            ->setUtmSource($data['utm_source'] ?? null);

        $handler = new CreateOrderFromApiHandler($client, $orderBuilder, Store::where('phone', $data['store_phone'])->first());
        $order = $handler->handle();

        return response()->json(['order' => $order ? $order->id : null], 200);
    }
}