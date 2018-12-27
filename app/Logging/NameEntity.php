<?php


namespace App\Logging;


abstract class NameEntity
{
    private static $entyties = [
        'App\Models\Realization' => 'Продукты',
        'App\Order' => 'Заказ',
        'App\Client' => 'Клиет',
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