<?php
/**
 * Created by PhpStorm.
 * User: aleksru
 * Date: 09.12.2018
 * Time: 22:18
 */

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;


use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
use App\Models\Supplier;
use App\Order;

class Report implements DataInterface
{

    /**
     * Основной массив данных
     * @var array
     */
    private $data = [
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
    private $toDate = null;

    /**
     * Report constructor.
     * @param string|null $date
     */
    public function __construct (string $date = null)
    {
        $this->toDate = $date;
    }

    /**
     * Внесение данных из заказа в массив
     * @return $this
     */
    public function prepareData()
    {
        $this->data['month'] = $this->toDate ? get_rus_month(date("m", strtotime($this->toDate)) - 1) :
            get_rus_month((int)date('m') - 1);
        $this->data['day'] = $this->toDate ? date("d", strtotime($this->toDate)) : date('d');
        $this->data['year'] = $this->toDate ? date("Y", strtotime($this->toDate)) : date('Y');

        $numb = 1;

        foreach (Order::toDay($this->toDate)->with('products', 'operator', 'client', 'deliveryPeriod', 'courier', 'metro')->get() as $order) {
            $this->product['product.date'] =  $this->data['day'].' '.$this->data['month'].' '.$this->data['year'];
            $this->product['product.operator'] = $order->operator ? $order->operator->name : '';
            $this->product['product.order'] = $order->id;
            $this->product['product.type'] = $order->comment ?? '';
            $this->product['product.store'] = $order->store ? $order->store->name : '';
            $this->product['product.client_name'] = $order->client ? $order->client->name ?? '' : '';
            $this->product['product.delivery_time'] = $order->deliveryPeriod ? $order->deliveryPeriod->period : '';
            $this->product['product.address'] = ($order->metro ? 'м.'.$order->metro->name.',' : '' )
                                                    .' '. ($order->address ?? '');
            $this->product['product.client_phone'] = $order->client ? $order->client->phone : '';
            $this->product['product.courier_name'] = $order->courier->name ?? '';

            if ($order->products->isEmpty()) {
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

            foreach ($order->products as $product) {

                $this->product['product.index'] = $numb;
                $this->product['product.name'] = $product->product_name ?? '';
                $this->product['product.imei'] = $product->pivot->imei ?? '';
                $this->product['product.quantity'] = $product->pivot->quantity ?? '';
                $this->product['product.price_opt'] = $product->pivot->price_opt ?? '';
                $this->product['product.price'] = $product->pivot->price ?? '';
                $this->product['product.courier_payment'] = $product->pivot->courier_payment ?? '';
                $this->product['product.profit'] = (int)$product->pivot->price - (int)$product->pivot->price_opt - (int)$product->pivot->courier_payment;
                $this->product['product.supplier'] = Supplier::find($product->pivot->supplier_id)->name ?? '';

                ++$numb;
                array_push($this->data['product'], $this->product);
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
        return 'Отчет от '.($this->toDate ? $this->toDate : date("d.m.Y")).'.xlsx';
    }
}