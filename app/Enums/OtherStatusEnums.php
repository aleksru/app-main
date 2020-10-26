<?php

namespace App\Enums;


class OtherStatusEnums
{
    /**
     * Тип подстатус для заказа
     */
    const SUBSTATUS_TYPE = 'SUBSTATUS';

    /**
     * Тип склада для статуса
     */
    const STOCK_TYPE = 'STOCK';

    /**
     * Тип логистики для статуса
     */
    const LOGISTIC_TYPE = 'LOGISTIC';

    /**
     * Тип статуса реализация
     */
    const REALIZATION_STATUS_TYPE = 'REALIZATION_STATUS';

    /**
     * Получение всех типов статусов
     *
     * @return array
     */
    public static function getAllTypesOtherStatuses():array
    {
        return [
            self::SUBSTATUS_TYPE,
            self::LOGISTIC_TYPE,
            self::STOCK_TYPE,
            self::REALIZATION_STATUS_TYPE
        ];
    }
}
