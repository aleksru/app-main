<?php

namespace App\Jobs;

use App\Client;
use App\Order;
use App\Services\Order\CreateOrderFromCallHandler;
use App\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
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
        Log::channel('order-calls')->error('CallCreateOrder RUN', $data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $builder = Order::getBuilder();
        $builder->setStoreText('No-' . $data['to']['number'] ?? '')
            ->setEntryId($data['entry_id']);
        $handler = new CreateOrderFromCallHandler(
            Client::getOrCreateClientFromPhone($data['from']['number']),
            $builder,
            Store::where('phone', $data['to']['number'])->first()
        );
        $order = $handler->handle();
        Log::channel('order-calls')->error('CallCreateOrder CREATE', $this->data, $order ? $order->id : $order);
    }
}
