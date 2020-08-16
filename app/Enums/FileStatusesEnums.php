<?php


namespace App\Enums;


class FileStatusesEnums
{
    const NO_PROCESS = 0;
    const SUCCESS = 1;
    const PROCESS = 2;
    const ERROR = 3;

    const DESCRIPTIONS = [
        self::NO_PROCESS => 'Не обработан',
        self::SUCCESS => 'Успешно обработан',
        self::PROCESS => 'В обработке',
        self::ERROR => 'Ошибка',
    ];

    public static function getDesc($status)
    {
        return self::DESCRIPTIONS[$status] ?? null;
    }
}
