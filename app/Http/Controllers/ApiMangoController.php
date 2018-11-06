<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCall;
use App\Product;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Order;

class ApiMangoController extends Controller
{
    public function index(Request $request)
    {
        $data = json_decode($request->json, true);

        //отладочные данные
//        $dataRequest = [
//            'json' => ["seq" => 1,"call_state" => "Appeared","location" => "ivr",
//              "from" => ["number" => "79264819924"],
//              "to" => ["number" => "74992881584", "line_number" => "74992881584"]
//              ],
//        ];
//        $data = $dataRequest['json'];

        //Проверка на первый входящий звонок
        if($data['seq'] == 1 && $data['location'] == 'ivr' && $data['call_state'] == 'Appeared'){
            //ищем клиента по номеру телефона
            $client = Client::getOnPhone($data['from']['number'])->first();
            //ищем магазин
            $store = Store::where('phone', $data['to']['number'])->first();
            //если новый клиент - создаем заявку
            if ( !$client ){
                $client = Client::create(['phone' => $data['from']['number']]);

                Order::create([
                    'name_customer' => 'Входящий Звонок',
                    'client_id' => $client->id,
                    'store' => $store->name ?? 'Не определен',
                    'phone' => $data['from']['number'],
                    'total' =>'0',
                    'comment' =>'-',
                    'products' => Product::EMPTY_PRODUCTS
                ]);
            }
            //фиксируем звонок
            ClientCall::create([
                'client_id' => $client->id,
                'type' => ClientCall::incomingCall,
                'store_id' => $store->id ?? NULL
            ]);
        }

        return ['status' => 200];
    }
}
