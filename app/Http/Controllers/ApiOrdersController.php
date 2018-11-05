<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Requests\ApiSetOrderRequest;
use App\Order;

class ApiOrdersController extends Controller
{
    public function api(ApiSetOrderRequest $req)
    {
        $data = $req->validated();
        $data['products'] = json_decode($req->products, true);
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);

        //ищем клиента, если не находим создаем
        $data['client_id'] = Client::firstOrCreate(['phone' => $data['phone']])->id;

        Order::create($data);
        
        return json_encode('ok');
    }
}