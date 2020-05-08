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

    /**
     * @return array
     */
    static function getConstants()
    {
        $oClass = new \ReflectionClass(static::class);
        return $oClass->getConstants();
    }

    public static function getDescription(int $result)
    {
        $res = self::getStatusesWithDescription();
        for($i = 0; $i < count($res); $i++){
            if($res[$i]['value'] == $result){
                return $res[$i]['label'];
            }
        }

        return null;
    }
}