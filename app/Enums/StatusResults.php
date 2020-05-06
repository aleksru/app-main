<?php


namespace App\Enums;


class StatusResults
{
    const SUCCESS = 1;
    const REFUSAL = 0;

    public static function getStatusesWithDescription() : array
    {
        return [
            ['value' => self::SUCCESS, 'label' => 'Успешно'],
            ['value' => self::REFUSAL, 'label' => 'Отказ']
        ];
    }
}