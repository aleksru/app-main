<?php


namespace App\Logging;


abstract class NameEntity
{
    private static $entyties = [
        'App\Models\Realization' => 'Продукт',
        'App\Order' => 'Заказ',
        'App\Client' => 'Клиент',
        'App\Models\ClientPhone' => 'Контакты клиента'
    ];

    /**
     * @param string $type
     * @return mixed|string
     */
    public static function getNameEntity(string $type)
    {
        return self::$entyties[$type] ?? '';
    }
}