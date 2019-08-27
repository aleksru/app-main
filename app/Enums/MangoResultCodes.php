<?php


namespace App\Enums;


class MangoResultCodes
{
    /**
     * @const codes descriptions
     */
     const CODES = [
        '1000' => 'Команда выполнена успешно',
        '2xxx' => 'Команда запрещена биллинговой системой ВАТС',
        '3100' => 'Переданы неверные параметры либо команда не может быть выполнена с этими параметрами',
        '4001' => 'Команда не поддерживается',
        '5ххх' => 'Ошибка сервера'
     ];

    /**
     * @param string $code
     * @return string
     */
     public static function getDescriptionCode($code) : string
     {
         if(array_key_exists($code, self::CODES)){
             return self::CODES[$code];
         } elseif ((int)$code >= 2000 && (int)$code < 3000){
             return self::CODES['2xxx'];
         } elseif ((int)$code >= 5000 && (int)$code < 6000) {
             return self::CODES['5ххх'];
         }
         
         return (string)$code;
     }
}