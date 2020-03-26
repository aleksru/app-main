<?php


namespace App\Enums;


class DeliveryInfoEnums
{
    const NAME = ['name' => 'Название организации', 'key' => 'delivery.name'];
    const INN = ['name' => 'ИНН', 'key' => 'delivery.inn'];
    const KPP = ['name' => 'КПП', 'key' => 'delivery.kpp'];
    const OGRN = ['name' => 'ОГРН', 'key' => 'delivery.ogrn'];
    const ADDRESS = ['name' => 'Адрес организации', 'key' => 'delivery.address'];
    const DIRECTOR = ['name' => 'Генеральный директор', 'key' => 'delivery.gendir'];


    public static function getDescriptions()
    {
        return [
            'name' => 'Название организации',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'ogrn' => 'ОГРН',
            'address' => 'Адрес организации',
            'gendir' => 'Генеральный директор',
        ];
    }

    public static function getDescriptionForField($field)
    {
        return self::getDescriptions()[$field] ?? null;
    }

    /**
     * @return array
     */
    static function getConstants() {
        $oClass = new \ReflectionClass(static::class);
        return $oClass->getConstants();
    }
}