<?php


namespace App\Services\Quickrun\Orders;


class QuickSetOrderData
{

    /**
    Id – строка (гуид), внутренний идентификатор системы
    timeFrom – строка, время доставки от в формате: часы:минуты (пример12:01)
    timeTo – строка,время доставки до в формате:часы:минуты(пример12:01)
    address – строка, адрес доставки
    buyerName – строка, ФИО получателя
    goods – строка, наименование товаров
    number – строка, номер заказа
    additionalInfo – строка, дополнительная информация
    price – число, цена
    phone – строка, номер телефона получателя
    dimensions –габариты груза
    duration – число, время задержки курьера на заказе в минутах
    length – число, длина
    width – число, ширина
    height – число, высота
    weight – число, вес
     */
//    public $id;
    public $timeFrom;
    public $timeTo;
    public $address;
    public $goods;
    public $buyerName;
    public $number;
    public $additionalInfo;
    public $price;
    public $phone;
    public $weight;
//    public $dimensions;
//    public $duration;
}