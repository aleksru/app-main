<?php

namespace App\Http\Controllers\Api;


use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Models\OrderStatus;
use App\Order;
use App\Product;
use App\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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
        //$data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        $customer = Client::getClientByPhone($data['phone']);
        $store = Store::where('phone', $data['store_phone'])->first();
        if($this->checkIgnoreOrder($store->name ?? '') && empty($data['products_text'])){
            return ;
        }
        if (!$customer) {
            $customer = Client::create([
                'phone' => $data['phone'],
                'name'  => $data['name_customer'] ?? 'Не указано',
            ]);
        }
        $statusNew = Cache::remember('ID_ORDER_STATUS_NEW', Carbon::now()->addHours(4) ,function(){
            return OrderStatus::getIdStatusNew();
        });

        if($statusNew && ($cntOrders = $customer->getOrdersCountForStatus($statusNew)) > 0){
            Log::error(['Создание заказа отклонено. Кол-во НОВЫХ заказов: ' . $cntOrders, $data]);
            return ;
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
            $cntProducts = 0;
            foreach ($order->products_text as $product) {
                if(isset($product['articul'])){
                    $productModel = Product::byActicle($product['articul'])->first();
                    if (!$productModel) {
                        continue;
                    }
                    if($cntProducts >= 10){
                        break;
                    }
                    $quantity = (int)$product['quantity'] ?? 1;
                    for ($i = 0; $i < $quantity; $i++){
                        if($i >= 10){
                            break;
                        }
                        $order->realizations()->create([
                            'quantity'   => 1,
                            'price'      => ($store && ! $store->is_disable_api_price) ? (float)$product['price'] : 0,
                            'product_id' => $productModel->id,
                        ]);
                    }
                    $cntProducts++;
                }
            }
        }

        return response()->json(['order' => $order->id], 200);
    }

    /**
     * @param $store_name
     * @return bool
     */
    private function checkIgnoreOrder($store_name)
    {
        $ignores = [];
        return in_array($store_name, $ignores);
    }

}