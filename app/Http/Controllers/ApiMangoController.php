<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCall;
use App\Enums\MangoCallEnums;
use App\Models\OrderStatus;
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
     * @return array
     */
    public function index(Request $request)
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
                $client = Client::getClientByPhone($data['from']['number']);
                //ищем магазин
                $store = Store::where('phone', $data['to']['number'])->first();
                //если новый клиент - создаем заявку
                if ( !$client ){
                    $client = Client::create(['phone' => $data['from']['number']]);
                    Order::create([
                        'client_id' => $client->id,
                        'store_text' => $store->name ?? 'No-' . $data['to']['number'] ?? '',
                        'phone' => $data['from']['number'],
                        'comment' =>'Входящий Звонок',
                        'store_id' => $store->id ?? null,
                        'status_id' => OrderStatus::getIdStatusNew() ?? null
                        //'products' => Product::EMPTY_PRODUCTS
                    ]);
                }
                //фиксируем звонок
                $client->calls()->create([
                    'type' => ClientCall::incomingCall,
                    'store_id' => $store->id ?? NULL,
                    'from_number' => $data['from']['number']
                ]);
            }

            //проверка на исходящий вызов
            if ($data['location'] ==='abonent' && array_key_exists('extension', $data['from'])){
                //ищем клиента по номеру телефона
                $client = Client::getClientByPhone($data['to']['number']);
                if ($client) {
                    //фиксируем звонок
                    $client->calls()->create([
                        'type' => ClientCall::outgoingCall,
                        'from_number' => $data['to']['number'] ?? null
                    ]);
                }

            }

        }

        return ['status' => 200];
    }

    /**
     * События по завершеню вызова
     *
     * @param Request $request
     */
    public function summary(Request $request)
    {
//        $data = array (
//            'entry_id' => 'NTg2NjkwNDM5NQ==',
//            'call_direction' => 1,
//            'from' =>
//                array (
//                    'number' => '79175872565',
//                ),
//            'to' =>
//                array (
//                    'extension' => '106',
//                    'number' => 'sip:user6@vpbx400137851.mangosip.ru',
//                ),
//            'line_number' => '74959266384',
//            'create_time' => 1551075342,
//            'forward_time' => 1551075342,
//            'talk_time' => 0,
//            'end_time' => 1551075443,
//            'entry_result' => 0,
//            'disconnect_reason' => 1110,
//        );
        $data = json_decode($request->json, true);

        //входящий звонок
        if($data['call_direction'] === MangoCallEnums::CALL_DIRECTION_INCOMING) {

            //пропущенный
            if($data['entry_result'] === MangoCallEnums::CALL_RESULT_MISSED) {
                if ($lastCall = ClientCall::getLastCallForNumber($data['from']['number'])){
                    $lastCall->status_call = MangoCallEnums::CALL_RESULT_MISSED;
                    $lastCall->save();
                }
            }
        }
    }
}
