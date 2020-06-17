<?php

namespace App\Jobs;

use App\Client;
use App\Enums\TypeCreatedOrder;
use App\Models\OrderStatus;
use App\Order;
use App\Repositories\OrderStatusRepository;
use App\Store;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
            //Log::channel('custom')->error($data);
            Order::create([
                'client_id'         => $client->id,
                'store_text'        => $store->name ?? 'No-' . $data['to']['number'] ?? '',
                'phone'             => $data['from']['number'],
                'comment'           =>'Входящий Звонок',
                'store_id'          => $store->id ?? null,
                'status_id'         => OrderStatus::getIdStatusNew() ?? null,
                'type_created_order' => TypeCreatedOrder::CALL,
                'entry_id'           => $data['entry_id']
            ]);

            return ;
        }

        $statusNew = Cache::remember('ID_ORDER_STATUS_NEW', Carbon::now()->addHours(4) ,function(){
            return OrderStatus::getIdStatusNew();
        });

        if($statusNew && $client->getOrdersCountForStatus($statusNew) == 0) {
            $isComplaining = $store ? $client->isStoreComplaint($store->id) : false;
            $idStatusComp = app(OrderStatusRepository::class)->getIdsStatusComplaining();
            //Log::channel('custom')->error($data);
            Order::create([
                'client_id'         => $client->id,
                'store_text'        => $store->name ?? 'No-' . $data['to']['number'] ?? '',
                'phone'             => $data['from']['number'],
                'comment'           => $isComplaining ? 'Звонок клиента по претензии' : 'Входящий Звонок',
                'store_id'          => $store->id ?? null,
                'status_id'         => $isComplaining ? $idStatusComp : $statusNew,
                'type_created_order' => TypeCreatedOrder::CALL,
                'entry_id'          => $data['entry_id']
            ]);
        }
    }
}
