<?php

namespace App\Enums;


class MangoCallEnums
{
    /**
     * Направление вызовов
     */

    //внутренний
    const CALL_DIRECTION_INTERNAL   = 0;

    //входящий
    const CALL_DIRECTION_INCOMING   = 1;

    //исходящий
    const CALL_DIRECTION_OUTCOMING  = 2;


    /**
     * Результат звонков
     */

    //звонок успешен и разговор состоялся
    const CALL_RESULT_SUCCESS  = 1;

    //звонок пропущен, разговор не состоялся
    const CALL_RESULT_MISSED   = 0;

    //Группа дозвона рекламаций
    const CALL_GROUP_COMPLAINT = 666;
}