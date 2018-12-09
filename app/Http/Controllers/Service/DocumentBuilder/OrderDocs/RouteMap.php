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
        $this->data['courier_name' ] = $this->courier->name;

        $numb = 1;
        foreach ($this->courier->orders()->deliveryToday($this->toDate)->with('products')->get() as $order) {
            foreach ($order->products as $product) {
                for ($i = 0; $i < $product->pivot->quantity; $i++) {
                    $this->product['product.index'] = $numb;
                    $this->product['product.client_name'] =  $order->client->name ?? '';
                    $this->product['product.delivery_time'] = $order->deliveryPeriod->period ?? '';
                    $this->product['product.address'] = ($order->metro ? 'м.'.$order->metro->name.',' : '' )
                        .' '. ($order->address ?? '');
                    $this->product['product.client_phone'] = $order->client->phone ?? '';
                    $this->product['product.name'] = $product->product_name;
                    $this->product['product.imei'] = $product->pivot->imei ?? '';
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