<?php

namespace App\Http\Controllers;

use App\Client;
use App\Product;
use App\Repositories\ClientRepository;
use App\Store;
use Illuminate\Http\Request;
use App\Http\Requests\ApiSetOrderRequest;
use App\Order;

class ApiOrdersController extends Controller
{
    /**
     * Создание заказа
     *
     * @param ApiSetOrderRequest $req
     * @param ClientRepository $clientRepository
     * @return string
     */
    public function api(ApiSetOrderRequest $req, ClientRepository $clientRepository)
    {
        $data = $req->validated();
        $data['products_text'] = json_decode($req->products, true);
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);

        //ищем клиента, если не находим создаем
        $client = $clientRepository->getClientByPhone($data['phone']);
        if(!$client) {
            $client = Client::create(['phone' => $data['phone']]);
        }

        $client->name = $client->name ? $client->name : $data['name_customer'] ?? 'Не указано';
        $client->save();

        $data['client_id'] = $client->id;

        $store = Store::where('phone', $data['store_id'])->first();
        $data['store_id'] = $store ? $store->id : null;

        $order = Order::create($data);

        if ($order->products_text){
            foreach ($order->products_text as $product) {
                $productModel = Product::byActicle($product['articul'])->first();

                if (!$productModel) {
                    continue;
                }

                $order->realizations()->create(['quantity' => (int)$product['quantity'], 'price' => (float)$product['price'], 'product_id' => $productModel->id]);
            }
        }
        
        return json_encode('ok');
    }
}