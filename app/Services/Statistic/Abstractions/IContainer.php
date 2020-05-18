<?php


namespace App\Services\Statistic\Abstractions;


interface IContainer
{
    public function addField(string $key, BaseStatisticItem $value);

    public function getField($key): ?BaseStatisticItem;

    public function getContainer();
}