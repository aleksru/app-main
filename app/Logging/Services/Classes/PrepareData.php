<?php

namespace App\Logging\Services\Classes;


class PrepareData
{
    private const DATA = [
        'App\Order' => [
            'colums' => [
                'store_text' => 'Магазин(строка)',
                'comment' => 'Комментарий',
                'id' => '№',
                'date_delivery' => 'Дата доставки',
                'address' => 'Адрес',
                'communication_time' => 'Время созвона',
                'flag_denial_acc' => 'Отказ от аксессуаров',
            ],

           'relationsColums' => [
                'client_id' => ['relation' => 'client', 'column' => 'name', 'name' => 'Клиент'],
                'status_id' => ['relation' => 'status', 'column' => 'status', 'name' => 'Статус'],
                'courier_id' => ['relation' => 'courier', 'column' => 'name', 'name' => 'Курьер'],
                'delivery_period_id' => ['relation' => 'deliveryPeriod', 'column' => 'period', 'name' => 'Время доставки'],
                'operator_id' => ['relation' => 'operator', 'column' => 'name', 'name' => 'Оператор'],
                'metro_id' => ['relation' => 'metro', 'column' => 'name', 'name' => 'Метро'],
                'store_id' => ['relation' => 'store', 'column' => 'name', 'name' => 'Магазин'],
                'denial_reason_id' => ['relation' => 'denialReason', 'column' => 'reason', 'name' => 'Причина отказа'],
                'delivery_type_id' => ['relation' => 'deliveryType', 'column' => 'type', 'name' => 'Тип доставки'],
            ],
        ],
        'App\Client' => [
            'colums' => [
                'name' => 'ФИО',
                'phone' => 'Телефон',
                'id' => '№',
                'description' => 'Описание'
            ],

            'relationsColums' => [],
        ],

        'App\Models\ClientPhone' => [
            'colums' => [
                'phone' => 'Доп телефон',
                'main' => 'Основной',
            ],

            'relationsColums' => [
                'client_id' => ['relation' => 'client', 'column' => 'name', 'name' => 'Клиент']
            ],
        ],

        'App\Models\Realization' => [
            'colums' => [
                'courier_payment' => 'Зп курьера',
                'delta' => 'Прибыль',
                'price' => 'Цена',
                'price_opt' => 'Закупка',
                'quantity' => 'Кол-во',
                'id' => '№',
            ],

            'relationsColums' => [
                'product_id' => ['relation' => 'product', 'column' => 'product_name', 'name' => 'Товар'],
                'order_id' => ['relation' => 'order', 'column' => 'id', 'name' => '№Заказа'],
                'supplier_id' => ['relation' => 'supplier', 'column' => 'name', 'name' => 'Поставщик'],
            ],
        ],

    ];

    /**
     * @param string $entity
     * @return bool|mixed
     */
    public static function getNavigationColumns(string $entity)
    {
        if(array_key_exists($entity, self::DATA)) {
            return self::DATA[$entity];
        }

        return false;
    }
}