<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCall;
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
//              ],
//        ];
//        $data = $dataRequest['json'];

        //Проверка на первый входящий звонок
        if($data['seq'] == 1 && $data['location'] == 'ivr' && $data['call_state'] == 'Appeared'){
            //ищем клиента по номеру телефона
            $client = Client::getOnPhone($data['from']['number'])->first();
            //если новый клиент - создаем заявку
            if ( !$client ){
                $client = Client::create(['phone' => $data['from']['number']]);
                Order::create([
                    'name_customer' => $client->name ?? 'Новый Звонок',
                    'client_id' => $client->id,
                    'store' => 'Звонок',
                    'phone' => $data['from']['number'],
                    'total' =>'0',
                    'comment' =>'-',
                    'products' => [['quantity' => '', 'articul' => '', 'name' => '', 'price' => '']]
                ]);
            }
            //фиксируем звонок
            ClientCall::create([ 'client_id' => $client->id ]);
        }

        return ['status' => 200];
    }
}
