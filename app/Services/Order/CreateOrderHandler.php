<?php


namespace App\Services\Order;

use App\Client;
use App\Order;
use App\OrderBuilder;
use App\Store;
use Illuminate\Support\Facades\Log;

class CreateOrderHandler
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var OrderBuilder
     */
    protected $orderBuilder;

    /**
     * @var Store|null
     */
    protected $store;

    /**
     * CreateOrderHandler constructor.
     * @param Client $client
     * @param OrderBuilder $orderBuilder
     * @param Store|null $store
     */
    public function __construct(Client $client, OrderBuilder $orderBuilder, ?Store $store = null)
    {
        $this->client = $client;
        $this->orderBuilder = $orderBuilder;
        $this->store = $store;
    }

    /**
     * @return Order|null
     */
    public function handle(): ?Order
    {
        if( ! $this->validateClientCountOrders() ){
            Log::error(['Создание заказа отклонено. Кол-во НОВЫХ заказов: ' .  $this->client->id]);
            return null;
        }
        $this->setStatus();
        $this->orderBuilder->setClient($this->client);
        $this->orderBuilder->setStore($this->store);
        $this->checkClient();

        return $this->orderBuilder->create();
    }

    protected function setStatus()
    {
        $this->orderBuilder->setStatusNew();
    }

    protected function checkClient()
    {
        $isStoreComplaint = $this->store ? $this->client->isStoreComplaint($this->store->id) : false;
        if($isStoreComplaint){
            $this->orderBuilder->setStatusComplaint();
            $this->orderBuilder->addComment('Обращение клиента по претензии!');
        }

        if($this->client->isLoyal()){
            $this->orderBuilder->addComment('Лояльный клиент');
        }

        if( $this->client->isComplaining() && ! $isStoreComplaint ){
            $this->orderBuilder->addComment('Негатив. Были жалобы в магазинах.');
        }
    }

    protected function validateClientCountOrders(): bool
    {
        return $this->client->getOrdersCountForStatusNew() === 0;
    }

}