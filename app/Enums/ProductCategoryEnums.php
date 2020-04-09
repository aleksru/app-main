<?php


namespace App\Enums;


class ProductCategoryEnums
{
    /**
     * Копия
     */
    const COPY = "COPY";

    /**
     * Наушники
     */
    const HEADPHONES = "HEADPHONES";

    /**
     * Восстановленные
     */
    const RESTORED = "RESTORED";

    /**
     * Оригинальные
     */
    const ORIGINAL = "ORIGINAL";

    /**
     * Подарок
     */
    const PRESENT = "PRESENT";

    /**
     * Приставка
     */
    const CONSOLE = "CONSOLE";

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
            ['name' => self::COPY, 'desc' => 'Копия'],
            ['name' => self::HEADPHONES, 'desc' => 'Наушники'],
            ['name' => self::RESTORED, 'desc' => 'Восстановленные'],
            ['name' => self::ORIGINAL, 'desc' => 'Оригинальные'],
            ['name' => self::PRESENT, 'desc' => 'Подарок'],
            ['name' => self::CONSOLE, 'desc' => 'Приставка'],
        ];
    }

    static function getCategoriesDescription() : array
    {
        return [
            self::COPY => 'Копия',
            self::HEADPHONES => 'Наушники',
            self::RESTORED => 'Восстановленные',
            self::ORIGINAL => 'Оригинальные',
            self::PRESENT => 'Подарок',
            self::CONSOLE => 'Приставка',
        ];
    }
}