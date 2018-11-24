<?php

namespace App\Http\Controllers;

use App\Client;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ApiSetOrderRequest;
use App\Order;

class ApiOrdersController extends Controller
{
    public function api(ApiSetOrderRequest $req)
    {
        $data = $req->validated();
        $data['products_text'] = json_decode($req->products, true);
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);

        //ищем клиента, если не находим создаем
        $client = Client::firstOrCreate(['phone' => $data['phone']]);
        $client->name = $client->name ? $client->name : $data['name_customer'] ?? 'Не указано';
        $client->save();

        $data['client_id'] = $client->id;

        $order = Order::create($data);

        foreach($order->products_text as $product) {
            $productModel = Product::byActicle($product['articul'])->first();

            if(! $productModel) {
                continue;
            }

            $order->products()->attach($productModel, ['quantity' => (int) $product['quantity'], 'price' => (float) $product['price']]);
        }
        
        return json_encode('ok');
    }
}