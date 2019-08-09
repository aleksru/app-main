<?php

namespace App\Enums;


class ProductType
{
    /**
     * Товар
     */
    const TYPE_PRODUCT = 'PRODUCT';

    /**
     * Аксессуар
     */
    const TYPE_ACCESSORY = 'ACCESSORY';

    /**
     * Услуга
     */
    const TYPE_SERVICE = 'SERVICE';

    /**
     * @return array
     */
    static function getConstants() {
        $oClass = new \ReflectionClass(static::class);
        return $oClass->getConstants();
    }

    /**
     * @return array
     */
    static function getConstantsForDescription() : array
    {
        return [
            ['name' => self::TYPE_PRODUCT, 'desc' => 'Товар'],
            ['name' => self::TYPE_ACCESSORY, 'desc' => 'Аксессуар'],
            ['name' => self::TYPE_SERVICE, 'desc' => 'Услуга'],
        ];
    }

}