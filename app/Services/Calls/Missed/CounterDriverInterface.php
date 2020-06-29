<?php


namespace App\Services\Calls\Missed;


use Carbon\Carbon;

interface CounterDriverInterface
{
    public static function increase(Carbon $carbon, string $type);

    public static function getCountByType(Carbon $carbon, string $type): int;
}