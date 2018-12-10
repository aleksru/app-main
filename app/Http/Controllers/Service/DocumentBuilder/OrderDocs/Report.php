<?php
/**
 * Created by PhpStorm.
 * User: aleksru
 * Date: 09.12.2018
 * Time: 22:18
 */

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;


use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
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
        'product.order' => 0,
        'product.name' => 0,
        'product.quantity' => '',
        'product.price' => '',
        'product.type' => '',
        'product.store' => '',
        'product.test' => '',

    ];

    /**
     * @var null
     */
    private $toDate = null;

    /**
     * RouteMap constructor.
     * @param Courier $courier
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
        foreach (Order::toDay($this->toDate)->with('products')->get() as $order) {
            $this->product['product.order'] = $order->id;
            $this->product['product.type'] = $order->comment ?? '';
            $this->product['product.store'] = $order->store ? $order->store->name : '';

            if ($order->products->isEmpty()) {
                $this->product['product.index'] = $numb;
                $this->product['product.name'] = '';
                $this->product['product.quantity'] = '';
                $this->product['product.price'] = '';
                array_push($this->data['product'], $this->product);
                ++$numb;
                continue;
            }

            foreach ($order->products as $product) {
                $this->product['product.index'] = $numb;
                $this->product['product.name'] = $product->product_name;
                $this->product['product.quantity'] = $product->pivot->quantity ?? '';
                $this->product['product.price'] = $product->pivot->price ?? '';
                $this->product['product.test'] = '';
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
        return 'Отчет от '.date("d.m.Y").'.xlsx';
    }
}