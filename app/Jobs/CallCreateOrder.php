<?php

namespace App\Jobs;

use App\Client;
use App\Models\OrderStatus;
use App\Order;
use App\Store;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class CallCreateOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private $data;

    /**
     * CallCreateOrder constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;

        //ищем клиента по номеру телефона
        $client = Client::getClientByPhone($data['from']['number']);
        //ищем магазин
        $store = Store::where('phone', $data['to']['number'])->first();
        if(in_array($data['to']['number'] ?? 0, Store::IGNORE_STORES_NUMBERS)){
            return ;
        }

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

            return ;
        }

        $statusNew = Cache::remember('ID_ORDER_STATUS_NEW', Carbon::now()->addHours(4) ,function(){
            return OrderStatus::getIdStatusNew();
        });

        if($statusNew && $client->getOrdersCountForStatus($statusNew) == 0) {
            $isComplaining = false;
            $idStatusComp = Cache::remember('ID_ORDER_STATUS_COMPLAINT', Carbon::now()->addHours(4) ,function(){
                return OrderStatus::getIdStatusForType(OrderStatus::STATUS_COMPLAINT_PREFIX);
            });

            if($idStatusComp){
                $isComplaining = $client->getOrdersCountForStatus($idStatusComp) > 0;
            }

            Order::create([
                'client_id'  => $client->id,
                'store_text' => $store->name ?? 'No-' . $data['to']['number'] ?? '',
                'phone'      => $data['from']['number'],
                'comment'    => $isComplaining ? 'Звонок клиента по претензии' : 'Входящий Звонок',
                'store_id'   => $store->id ?? null,
                'status_id'  => $isComplaining ? $idStatusComp : $statusNew
            ]);
        }
    }
}
