<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientCall;
use App\Enums\MangoCallEnums;
use App\Jobs\ClientCallConnected;
use App\Jobs\SaveCall;
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
     * Создание заявки по первому звонку клиента
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
                if ( ! $client ){
                    $client = Client::create(['phone' => $data['from']['number']]);
                    Order::create([
                        'client_id' => $client->id,
                        'store_text' => $store->name ?? 'No-' . $data['to']['number'] ?? '',
                        'phone' => $data['from']['number'],
                        'comment' =>'Входящий Звонок',
                        'store_id' => $store->id ?? null,
                        'status_id' => OrderStatus::getIdStatusNew() ?? null
                    ]);

                    return ['status' => 200];
                }

                $statusNew = OrderStatus::getIdStatusNew();
                if($statusNew && $client->getOrdersCountForStatus($statusNew) == 0) {
                    Order::create([
                        'client_id' => $client->id,
                        'store_text' => $store->name ?? 'No-' . $data['to']['number'] ?? '',
                        'phone' => $data['from']['number'],
                        'comment' =>'Входящий Звонок',
                        'store_id' => $store->id ?? null,
                        'status_id' => $statusNew
                    ]);
                }
            }
        }

        //оператор снимает трубку
        if($data['seq'] === 2 && $data['call_state'] === 'Connected' && $data['location'] === 'abonent'){
            //входящий
            if(preg_match('/^(\d){1,12}$/', $data['from']['number'])){
                //Log::channel('custom')->error($data);
                ClientCallConnected::dispatch($data);
            }
        }

        return ['status' => 200];
    }

    /**
     * События по завершеню вызова
     *
     * @param Request $request
     */
    public function summary(Request $request) : void
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
        $data = json_decode($request->json, true);
        SaveCall::dispatch($data)->onQueue('calls');
    }
}
