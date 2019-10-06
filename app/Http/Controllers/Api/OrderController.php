<?php

namespace App\Http\Controllers\Api;


use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Models\OrderStatus;
use App\Order;
use App\Product;
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
        //Log::error($data);
        $data['products_text'] = json_decode($createOrderRequest->products, true);
        //$data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        $customer = Client::getClientByPhone($data['phone']);
        $store = Store::where('phone', $data['store_phone'])->first();

        if (!$customer) {
            $customer = Client::create([
                'phone' => $data['phone'],
                'name'  => $data['name_customer'] ?? 'Не указано',
            ]);
        }

        $order = Order::create([
            'status_id'     => OrderStatus::getIdStatusNew(),
            'client_id'     => $customer->id,
            'store_text'    => $data['store_text'],
            'store_id'      => $store->id ?? null,
            'comment'       => $data['comment'] ?? '',
            'products_text' => $data['products_text'] ?? [],
            'utm_source'    => $data['utm_source'] ?? null,
        ]);

        if ($order->products_text) {
            foreach ($order->products_text as $product) {
                if(isset($product['articul'])){
                    $productModel = Product::byActicle($product['articul'])->first();
                    if (!$productModel) {
                        continue;
                    }
                    $quantity = (int)$product['quantity'] ?? 1;
                    for ($i = 0; $i < $quantity; $i++){
                        $order->realizations()->create([
                            'quantity'   => 1,
                            'price'      => ($store && ! $store->is_disable_api_price) ? (float)$product['price'] : 0,
                            'product_id' => $productModel->id,
                        ]);
                    }

                }
            }
        }

        return response()->json(['order' => $order->id], 200);
    }

}