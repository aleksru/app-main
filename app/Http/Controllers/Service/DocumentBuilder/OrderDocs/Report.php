<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;

use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
use App\Order;
use Illuminate\Database\Eloquent\Collection;

class Report extends BaseReport
{

    /**
     * Основной массив данных
     * @var array
     */
    protected $data = [
        'day' => '',
        'month' => '',
        'year' => '',
        'product' => []
    ];

    /**
     * Шаблон массив продуктов
     * @var array
     */
    private $product = [
        'product.index' => 1,
        'product.date' => '0',
        'product.operator' => '0',
        'product.store' => '0',
        'product.order' => 0,
        'product.type' => '0',
        'product.client_name' => '0',
        'product.delivery_time' => '0',
        'product.address' => '0',
        'product.client_phone' => '0',
        'product.status' => '0',


        'product.name' => '0',
        'product.imei' => '0',
        'product.quantity' => '0',
        'product.price_opt' => '0',
        'product.price' => '0',
        'product.courier_payment' => '0',
        'product.profit' => '0',
        'product.courier_name' => '0',
        'product.supplier' => '0',
        'product.test' => '',

    ];

    /**
     * @var null
     */
    private $orders;

    /**
     * Report constructor.
     * @param string|null $date
     */
    public function __construct (Collection $orders)
    {
        $this->orders = $orders;
    }

    /**
     * Внесение данных из заказа в массив
     * @return $this
     */
    public function prepareData()
    {
        $this->data['month'] = get_rus_month((int)date('m') - 1);
        $this->data['day'] = date('d');
        $this->data['year'] =  date('Y');

        $numb = 1;

        foreach ($this->orders as $order) {
            $this->product['product.date'] =  date("d.m.Y", strtotime($order->created_at));
            $this->product['product.operator'] = $order->operator ? $order->operator->name : '';
            $this->product['product.order'] = $order->id;
            $this->product['product.type'] = $order->comment ?? '';
            $this->product['product.store'] = $order->store ? $order->store->name : '';
            $this->product['product.client_name'] = $order->client ? $order->client->name ?? '' : '';
            $this->product['product.delivery_time'] =
                ($order->date_delivery ?? '') . ' ' . ($order->deliveryPeriod ? $order->deliveryPeriod->period : '');
            $this->product['product.address'] = ($order->metro ? 'м.'.$order->metro->name.',' : '' )
                                                    .' '. ($order->fullAddress ?? '');
            $this->product['product.client_phone'] = $order->client ? $order->client->phone : '';
            $this->product['product.courier_name'] = $order->courier->name ?? '';
            $this->product['product.status'] = $order->status? $order->status->status : '';

            if ($order->realizations->isEmpty()) {
                $this->product['product.index'] = $numb;
                $this->product['product.name'] = '';
                $this->product['product.imei'] = '';
                $this->product['product.quantity'] = '';
                $this->product['product.price_opt'] = '';
                $this->product['product.price'] = '';
                $this->product['product.courier_payment'] = '';
                $this->product['product.profit'] = '';
                $this->product['product.supplier'] = '';

                array_push($this->data['product'], $this->product);

                ++$numb;
                continue;
            }

            foreach ($order->realizations as $product) {

                $this->product['product.index'] = $numb;
                $this->product['product.name'] = $product->product->product_name ?? '';
                $this->product['product.imei'] = $product->imei ?? '';
                $this->product['product.quantity'] = $product->quantity ?? '';
                $this->product['product.price_opt'] = $product->price_opt ?? '';
                $this->product['product.price'] = $product->price ?? '';
                $this->product['product.courier_payment'] = $product->courier_payment ?  $product->courier_payment + ($order->deliveryType->price ?? 0) : $order->deliveryType->price ?? 0;
                $this->product['product.profit'] = (int)$product->price - (int)$product->price_opt - (int)$product->courier_payment;
                $this->product['product.supplier'] = $product->supplier ? $product->supplier->name : '';
                $this->product['product.product_id'] = $product->product->id ?? '';;
                ++$numb;
                array_push($this->data['product'], $this->product);
            }
        }

        return $this;
    }

    /**
     * Имя файла
     * @return string
     */
    public function getFileName() : string
    {
        return 'Отчет от '.(date("d.m.Y")).'.xlsx';
    }

    /**
     * @return string
     */
    public function getTemplatePath() : string
    {
        return  storage_path('app' . DIRECTORY_SEPARATOR . 'exel_templates' . DIRECTORY_SEPARATOR . 'every_day_report.xlsx');
    }

    /**
     * @return array
     */
    public function getResultsData() : array
    {
        return $this->data;
    }
}