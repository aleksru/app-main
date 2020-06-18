<?php


namespace App;


use App\Enums\TypeCreatedOrder;
use App\Repositories\OrderStatusRepository;

class OrderBuilder
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * OrderBuilder constructor.
     */
    public function __construct()
    {
        $this->order = new Order();
        $this->order->store_text = 'N/A';
    }

    public function setStatusNew():self
    {
        $this->order->status_id = app(OrderStatusRepository::class)->getIdStatusNew();
        return $this;
    }

    public function setStatusComplaint():self
    {
        $this->order->status_id = app(OrderStatusRepository::class)->getIdsStatusComplaining();
        return $this;
    }

    /**
     * @param Store|null $store
     * @return OrderBuilder
     */
    public function setStore(?Store $store):self
    {
        if($store){
            $this->order->store_id = $store->id;
        }

        return $this;
    }

    public function setClient(Client $client):self
    {
        $this->order->client_id = $client->id;
        return $this;
    }

    public function setTypeCreateApi():self
    {
        $this->order->type_created_order = TypeCreatedOrder::API;
        return $this;
    }

    public function setTypeCreateCall():self
    {
        $this->order->type_created_order = TypeCreatedOrder::CALL;
        return $this;
    }

    public function setTypeCreateCustom():self
    {
        $this->order->type_created_order = TypeCreatedOrder::CUSTOM;
        return $this;
    }

    public function setComment(?string $comment):self
    {
        $this->order->comment = $comment;
        return $this;
    }

    public function addComment(?string $comment):self
    {
        $this->order->comment = $this->order->comment . "\n" . $comment;
        return $this;
    }

    /**
     * @param array|null $products
     * @return OrderBuilder
     */
    public function setProductText(?array $products):self
    {
        $this->order->products_text = $products;
        return $this;
    }

    /**
     * @param null|string $utmSource
     * @return OrderBuilder
     */
    public function setUtmSource(?string $utmSource):self
    {
        $this->order->utm_source = $utmSource;
        return $this;
    }

    public function setStoreText(string $storeText):self
    {
        $this->order->store_text = $storeText;
        return $this;
    }

    /**
     * @param int|null $idUser
     * @return $this
     */
    public function setUserCreator(?int $idUser)
    {
        $this->order->creator_user_id = $idUser;
        return $this;
    }

    public function setEntryId(string $entryId)
    {
        $this->order->entry_id = $entryId;
        return $this;
    }

    public function create(): Order
    {
        $this->order->save();
        return $this->order;
    }
}