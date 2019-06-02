<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;

use App\Order;

class MarketCheckData extends BaseReport
{

    /**
     * Основной массив данных
     * @var array
     */
    protected $data = [
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
        $clientPhones = $this->order->client->phone;
        foreach($this->order->client->additionalPhones as $additionalPhone){
            $clientPhones = $clientPhones.', '. ($additionalPhone->main ? 'Основной: '.$additionalPhone->phone : $additionalPhone->phone);
        }

        $this->data['id' ] = $this->order->id;
        $this->data['date' ] = date("d.m.Y");
        $this->data['client_name' ] = $this->order->client->name ?? '';
        $this->data['client_phone' ] =  $clientPhones;
        $this->data['client_address' ] = ($this->order->metro ? 'м.'.$this->order->metro->name.',' : '' )
                                            .' '. ($this->order->fullAddress ?? '');
        $this->data['delivery_period' ] = $this->order->deliveryPeriod->period ?? '';
        $this->data['site'] = $this->order->store ? $this->order->store->name : '';

        $numb = 1;
        foreach ($this->order->realizations as $product) {
            $this->products['product.index'] = $numb;
            $this->products['product.name'] = $product->product->product_name;
            $this->products['product.imei'] = $product->imei ?? '';
            $this->products['product.quantity'] =  $product->quantity ?? '';
            $this->products['product.price'] = $product->price ?? '';
            $this->products['product.summ'] = $product->price && $product->quantity ?
                                                    ((int)$product->price * (int)$product->quantity) : 0;
            $this->products['product.test'] = '';
            ++$numb;

            array_push($this->data['product'], $this->products);
        }
        $this->setCorpInfo();

        return $this;
    }

    /**
     * Имя файла
     * @return string
     */
    public function getFileName() : string
    {
        return 'Счет №'.$this->order->id.' от '.date("d.m.Y").'.xlsx';
    }

    /**
     * @return string
     */
    public function getTemplatePath() : string
    {
        return  storage_path('app' . DIRECTORY_SEPARATOR . 'exel_templates' . DIRECTORY_SEPARATOR . 'market_check.xlsx');
    }

}