<?php


namespace App\Services\Quickrun\Orders;


use App\Services\Quickrun\QuickrunClient;
use Carbon\Carbon;

class QuickrunOrder
{
    /**
     * @var QuickrunClient
     */
    private $client;

    /**
     * @var array
     */
    private $orders = [];

    /**
     * QuickrunOrder constructor.
     */
    public function __construct()
    {
        $this->client = new QuickrunClient();
    }

    /**
     * @param Carbon|null $carbon
     * @return \GuzzleHttp\Psr7\Response|null
     */
    public function setOrders(?Carbon $carbon = null)
    {
        if(!$carbon){
            $carbon = Carbon::today();
        }

        return $this->client->post('client/orders/' . $carbon->toDateString(), $this->orders);
    }

    /**
     * @param QuickSetOrderData $quickSetOrderData
     * @return QuickrunOrder
     */
    public function addOrder(QuickSetOrderData $quickSetOrderData) : self
    {
        $this->orders[] = (array)$quickSetOrderData;

        return $this;
    }
}