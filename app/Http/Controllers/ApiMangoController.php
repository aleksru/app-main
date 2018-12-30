<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCall;
use App\Product;
use App\Repositories\ClientRepository;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Order;

class ApiMangoController extends Controller
{
    /**
     * Фикс звонков
     *
     * @param Request $request
     * @param ClientRepository $clientRepository
     * @return array
     */
    public function index(Request $request, ClientRepository $clientRepository)
    {
        $data = json_decode($request->json, true);

//отладочные данные
        //входищий
//        $dataRequest = [
//            'json' => ["seq" => 1,"call_state" => "Appeared","location" => "ivr",
//              "from" => ["number" => "79264819924"],
//              "to" => ["number" => "74992881584", "line_number" => "74992881584"]
//              ],
//        ];
//        $data = $dataRequest['json'];

//исходящий
//        $dataRequest = [
//            'json' => ["seq" => 1,"call_state" => "Appeared","location" => "abonent",
//              "from" => ["extension" => "111"],
//              "to" => ["number" => "79264819924"]
//              ],
//        ];
//        $data = $dataRequest['json'];

        //Проверка на первый звонок
        if($data['seq'] === 1 && $data['call_state'] === 'Appeared'){
            //проверка на входящий звонок
            if ($data['location'] === 'ivr') {
                //ищем клиента по номеру телефона
                $client = $clientRepository->getClientByPhone($data['from']['number']);
                //ищем магазин
                $store = Store::where('phone', $data['to']['number'])->first();
                //если новый клиент - создаем заявку
                if ( !$client ){
                    $client = Client::create(['phone' => $data['from']['number']]);
                    Order::create([
                        'client_id' => $client->id,
                        'store_text' => $store->name ?? 'Не определен',
                        'phone' => $data['from']['number'],
                        'comment' =>'Входящий Звонок',
                        'store_id' => $store->id ?? null,
                        //'products' => Product::EMPTY_PRODUCTS
                    ]);
                }
                //фиксируем звонок
                $client->calls()->create([
                    'type' => ClientCall::incomingCall,
                    'store_id' => $store->id ?? NULL
                ]);
            }

            //проверка на исходящий вызов
            if ($data['location'] ==='abonent' && array_key_exists('extension', $data['from'])){
                //ищем клиента по номеру телефона
                $client = $clientRepository->getClientByPhone($data['to']['number']);
                if ($client) {
                    //фиксируем звонок
                    $client->calls()->create(['type' => ClientCall::outgoingCall]);
                }

            }

        }

        return ['status' => 200];
    }
}
