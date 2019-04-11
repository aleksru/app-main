<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCall;
use App\Enums\MangoCallEnums;
use App\Models\Operator;
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
        $data = $this->prepareData($request->json);

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
                    ]);
                }
                //фиксируем звонок
                $client->calls()->create([
                    'type' => ClientCall::incomingCall,
                    'store_id' => $store->id ?? NULL,
                    'from_number' => $data['from']['number'],
                    'call_create_time' => $data['timestamp']
                ]);
            }

            /**
             * отключено
             * фиксация перенесена в события завершения
             * тестирование
             */
            //проверка на исходящий вызов
//            if ($data['location'] ==='abonent' && array_key_exists('extension', $data['from'])){
//                //ищем клиента по номеру телефона
//                $client = Client::getClientByPhone($data['to']['number']);
//                if ($client) {
//                    //фиксируем звонок
//                    $client->calls()->create([
//                        'type' => ClientCall::outgoingCall,
//                        'from_number' => $data['to']['number'] ?? null
//                    ]);
//                }
//
//            }

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
//                    'number' => '79250991030',
//                    //'number' => 'sip:user6@vpbx400137851.mangosip.ru',
//                ),
//            'to' =>
//                array (
//                    'extension' => '106',
//                    //'number' => '79250991030',
//                    'number' => 'sip:user6@vpbx400137851.mangosip.ru',
//                ),
//            'line_number' => '74959266384',
//            'create_time' => 1551073978,
//            'forward_time' => 1551095342,
//            'talk_time' => 0,
//            'end_time' => 1551085543,
//            'entry_result' => 0,
//            'disconnect_reason' => 1110,
//        );
        $data = $this->prepareData($request->json);

        //входящий звонок
        if($data['call_direction'] === MangoCallEnums::CALL_DIRECTION_INCOMING) {

            //пропущенный
            if($data['entry_result'] === MangoCallEnums::CALL_RESULT_MISSED) {
                if (isset($data['from']) &&
                            $lastCall = ClientCall::getCallByHash(ClientCall::makeHash([$data['from']['number'], $data['create_time']]))){
                    $lastCall->status_call = $data['entry_result'];
                    $lastCall->call_end_time = $data['end_time'] ?? null;
                    $lastCall->save();
                }else {
                    Log::error(['пропущенный', $data]);
                    $client = Client::getClientByPhone($data['from']['number']);
                    if(!$client){
                        $client = Client::create(['phone' => $data['from']['number']]);
                    }
                    $store = Store::where('phone', $data['line_number'])->first();
                    ClientCall::create([
                        'type' => ClientCall::incomingCall,
                        'from_number' => $data['from']['number'] ?? null,
                        'call_create_time' => $data['create_time'],
                        'call_end_time' => $data['end_time'],
                        'client_id' => $client->id ?? null,
                        'status_call' => $data['entry_result'],
                        'store_id' => $store->id ?? null
                    ]);
                }
            }

            //успешный
            if($data['entry_result'] === MangoCallEnums::CALL_RESULT_SUCCESS) {
                $getOperator = $data['to']['number'] ? Operator::getOperatorBySipLogin(explode(':', $data['to']['number'])[1]) : null;
                if (isset($data['from']) &&
                                $clientCall = ClientCall::getCallByHash(ClientCall::makeHash([$data['from']['number'], $data['create_time']]))){
                    $clientCall->call_end_time = $data['end_time'];
                    $clientCall->operator_text = $data['to']['number'] ?? null;
                    $clientCall->operator_id = $getOperator ? $getOperator->id : null;
                    $clientCall->status_call = $data['entry_result'];
                    $clientCall->save();
                }else{
                    Log::error(['успешный', $data]);
                    $client = Client::getClientByPhone($data['from']['number']);
                    if(!$client){
                        $client = Client::create(['phone' => $data['from']['number']]);
                    }
                    $store = Store::where('phone', $data['line_number'])->first();
                    ClientCall::create([
                        'type' => ClientCall::incomingCall,
                        'from_number' => $data['from']['number'] ?? null,
                        'call_create_time' => $data['create_time'],
                        'call_end_time' => $data['end_time'],
                        'client_id' => $client->id ?? null,
                        'status_call' => $data['entry_result'],
                        'store_id' => $store->id ?? null,
                        'operator_id' => $getOperator ? $getOperator->id : null,
                        'operator_text' => $data['to']['number'] ?? null,
                    ]);
                }
            }
        }

        //исходящий
        if($data['call_direction'] === MangoCallEnums::CALL_DIRECTION_OUTCOMING) {
            $operator = Operator::getOperatorBySipLogin(explode(':', $data['from']['number'])[1]);
            $client = Client::getClientByPhone($data['to']['number']);

            ClientCall::create([
                'type' => ClientCall::outgoingCall,
                'from_number' => $data['to']['number'] ?? null,
                'call_create_time' => $data['create_time'],
                'call_end_time' => $data['end_time'],
                'operator_text' => $data['from']['number'] ?? null,
                'client_id' => $client->id ?? null,
                'status_call' => $data['entry_result'],
                'operator_id' => $operator->id ?? null,
            ]);
        }
    }

    /**
     * @param $data
     * @return array
     */
    private function prepareData($data):array
    {
        $data = json_decode($data, true);

        if(isset($data['create_time'])){
            $data['create_time'] = substr($data['create_time'], 0, -1);
        }

        if(isset($data['timestamp'])){
            $data['timestamp'] = substr($data['timestamp'], 0, -1);
        }

        return $data;
    }
}
