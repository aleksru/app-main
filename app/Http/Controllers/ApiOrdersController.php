<?php

namespace App\Http\Controllers;

use App\Client;
use App\Models\OrderStatus;
use App\Product;
use App\Repositories\ClientRepository;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\ApiSetOrderRequest;
use App\Order;
use Illuminate\Support\Facades\Cache;
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
        Log::channel('api')->error($data);
        $data['products_text'] = json_decode($req->products, true);
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);

        //ищем клиента, если не находим создаем
        $client = Client::getClientByPhone($data['phone']);
        if(!$client) {
            $client = Client::create(['phone' => $data['phone']]);
        }
        $statusNew = Cache::remember('ID_ORDER_STATUS_NEW', Carbon::now()->addHours(4) ,function(){
            return OrderStatus::getIdStatusNew();
        });

        if($statusNew && ($cntOrders = $client->getOrdersCountForStatus($statusNew)) > 0){
            Log::error(['Создание заказа отклонено. Кол-во НОВЫХ заказов: ' . $cntOrders, $data]);
            return ;
        }

        $client->name = $client->name ? $client->name : $data['name_customer'] ?? 'Не указано';
        $client->save();

        $data['client_id'] = $client->id;
        $data['status_id'] = OrderStatus::getIdStatusNew();

        $store = Store::where('phone', $data['store_id'])->first();

        if($this->checkIgnoreOrder($store->name ?? '') && empty($data['products_text'])){
            return ;
        }
        $data['store_id'] = $store ? $store->id : null;

        $order = Order::create($data);

        if ($order->products_text){
            $cntProducts = 0;
            foreach ($order->products_text as $product) {
                if(!isset($product['articul']) && !is_string($product['articul'])){
                    continue;
                }
                if($cntProducts >= 10){
                    break;
                }
                $productModel = Product::byActicle($product['articul'])->first();

                if (!$productModel) {
                    continue;
                }
                $price = ($store && ! $store->is_disable_api_price) ? (float)$product['price'] : 0;
                $quantity = (int)$product['quantity'] > 0 ? (int)$product['quantity'] : 1;
                for ($i=0; $i < $quantity; $i++){
                    if($i >= 10){
                        break;
                    }
                    $order->realizations()->create([
                        'quantity' => 1,
                        'price' => $price,
                        'product_id' => $productModel->id,
                        'product_type' => $productModel->type
                    ]);
                }
                $cntProducts++;
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