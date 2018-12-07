<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;


use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
use App\Order;

class MarketCheckData implements DataInterface
{

    /**
     * Основной массив данных
     * @var array
     */
    private $data = [
        'id' => '',
        'date' => '',
        'client_name' => '',
        'client_phone' => '',
        'client_address' => '',
        'delivery_period' => '',
        'site' => '',
        'product' => []
    ];

    /**
     * Шаблон массив продуктов
     * @var array
     */
    private $products = [
        'product.index' => 1,
        'product.name' => '',
        'product.imei' => '',
        'product.quantity' => 0,
        'product.price' => 0,
        'product.summ' => 0,
        'product.test' => 0,
    ];

    /**
     * @var Order
     */
    private $order;

    /**
     * MarketCheckData constructor.
     * @param Order $order
     */
    public function __construct (Order $order)
    {
        $this->order = $order;
    }

    /**
     * Внесение данных из заказа в массив
     * @return $this
     */
    public function prepareData()
    {
        $this->data['id' ] = $this->order->id;
        $this->data['date' ] = date("d.m.Y");
        $this->data['client_name' ] = $this->order->client->name ?? '';
        $this->data['client_phone' ] = $this->order->client->phone ?? '';
        $this->data['client_address' ] = ($this->order->metro ? 'м.'.$this->order->metro->name.',' : '' )
                                            .' '. ($this->order->address ?? '');
        $this->data['delivery_period' ] = $this->order->deliveryPeriod->period ?? '';
        $this->data['site'] = $this->order->store ? $this->order->store->name : '';

        $numb = 1;
        foreach ($this->order->products as $product) {
            $this->products['product.index'] = $numb;
            $this->products['product.name'] = $product->product_name;
            $this->products['product.imei'] = $product->pivot->imei ?? '';
            $this->products['product.quantity'] =  $product->pivot->quantity ?? '';
            $this->products['product.price'] = $product->pivot->price ?? '';
            $this->products['product.summ'] = $product->pivot->price && $product->pivot->quantity ?
                                                    ((int)$product->pivot->price * (int)$product->pivot->quantity) : 0;
            $this->products['product.test'] = '';
            ++$numb;

            array_push($this->data['product'], $this->products);
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
        return 'Счет №'.$this->order->id.' от '.date("d.m.Y").'.xlsx';
    }

}