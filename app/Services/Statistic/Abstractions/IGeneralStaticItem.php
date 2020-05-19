<?php


namespace App\Services\Statistic\Abstractions;

use App\Services\Statistic\GeneralStatistic\GeneralItem;

interface IGeneralStaticItem
{
    function createGeneralItem($field): GeneralItem;
}