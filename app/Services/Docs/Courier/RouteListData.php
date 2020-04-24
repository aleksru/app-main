<?php
namespace App\Services\Docs\Courier;


use App\Order;
use Carbon\Carbon;

class RouteListData
{
    /**
     * @var Carbon;
     */
    private $dateDelivery;
    private $corporateInfo;
    private $orders;
    private $courier;

    /**
     * @return mixed
     */
    public function getCourier()
    {
        return $this->courier;
    }

    /**
     * @param mixed $courier
     */
    public function setCourier($courier)
    {
        $this->courier = $courier;
    }

    /**
     * @return string
     */
    public function getDateDelivery() : string
    {
        return $this->dateDelivery->format('d.m.Y');
    }

    /**
     * @param $dateDelivery
     */
    public function setDateDelivery(Carbon $dateDelivery)
    {
        $this->dateDelivery = $dateDelivery;
    }

    /**
     * @return mixed
     */
    public function getCorporateInfo()
    {
        return $this->corporateInfo;
    }

    /**
     * @param mixed $corporateInfo
     */
    public function setCorporateInfo($corporateInfo)
    {
        $this->corporateInfo = $corporateInfo;
    }

    /**
     * @return array
     */
    public function getOrders() : array
    {
        return $this->orders;
    }

    /**
     * @param array $orders
     */
    public function setOrders(array $orders)
    {
        foreach ($orders as $order){
            if( ! ($order instanceof Order) ){
                throw new \InvalidArgumentException('Field not instance App\Order');
            }
        }
        $this->orders = $orders;
    }



}