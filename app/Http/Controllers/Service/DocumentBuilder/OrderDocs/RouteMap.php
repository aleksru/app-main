<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;


use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
use App\Models\Courier;

class RouteMap implements DataInterface
{

    /**
     * Основной массив данных
     * @var array
     */
    private $data = [
        'courier_name' => '',
        'day' => '',
        'month' => '',
        'product' => []
    ];

    /**
     * Шаблон массив продуктов
     * @var array
     */
    private $product = [
        'product.index' => 1,
        'product.client_name' => 0,
        'product.delivery_time' => 0,
        'product.address' => '',
        'product.client_phone' => '',
        'product.name' => '',
        'product.imei' => '',

    ];

    /**
     * @var Courier
     */
    private $courier;

    /**
     * @var null
     */
    private $toDate = null;

    /**
     * RouteMap constructor.
     * @param Courier $courier
     */
    public function __construct (Courier $courier, string $date = null)
    {
        $this->courier = $courier;
        $this->toDate = $date;
    }

    /**
     * Внесение данных из заказа в массив
     * @return $this
     */
    public function prepareData()
    {
        $this->data['courier_name'] = $this->courier->name;
        $this->data['month'] = $this->toDate ? get_rus_month(date("m", strtotime($this->toDate)) - 1) :
                                                get_rus_month((int)date('m') - 1);
        $this->data['day'] = $this->toDate ? date("d", strtotime($this->toDate)) : date('d');

        $numb = 1;
        foreach ($this->courier->orders()->deliveryToday($this->toDate)->with('realizations')->get() as $order) {
            $clientPhones = $order->client->phone;
            foreach($order->client->additionalPhones as $additionalPhone){
                $clientPhones = $clientPhones.', '. ($additionalPhone->main ? 'Основной: '.$additionalPhone->phone : $additionalPhone->phone);
            }
            foreach ($order->realizations as $product) {
                for ($i = 0; $i < $product->quantity; $i++) {
                    $this->product['product.index'] = $numb;
                    $this->product['product.client_name'] =  $order->client->name ?? '';
                    $this->product['product.delivery_time'] = $order->deliveryPeriod->period ?? '';
                    $this->product['product.address'] = ($order->metro ? 'м.'.$order->metro->name.',' : '' )
                        .' '. ($order->address ?? '');
                    $this->product['product.client_phone'] = $clientPhones ?? '';
                    $this->product['product.name'] = $product->product->product_name;
                    $this->product['product.imei'] = $product->imei ?? '';
                    $this->product['product.test'] = '';
                    ++$numb;
                    array_push($this->data['product'], $this->product);
                }
            }

        }

        return $this;
    }

    /**
     * Получение данных
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Имя файла
     * @return string
     */
    public function getFileName()
    {
        return 'Маршрутный лист '.$this->courier->name.' от '.date("d.m.Y").'.xlsx';
    }
}