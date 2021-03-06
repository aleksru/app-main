<?php


namespace App\Enums;


class CorporateInfoEnums
{
    const NAME = ['name' => 'Название организации', 'key' => 'corporate.name'];
    const INN = ['name' => 'ИНН', 'key' => 'corporate.inn'];
    const KPP = ['name' => 'КПП', 'key' => 'corporate.kpp'];
    const OGRN = ['name' => 'ОГРН', 'key' => 'corporate.ogrn'];
    const ADDRESS = ['name' => 'Адрес организации', 'key' => 'corporate.address'];
    const DIRECTOR = ['name' => 'Генеральный директор', 'key' => 'corporate.gendir'];


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