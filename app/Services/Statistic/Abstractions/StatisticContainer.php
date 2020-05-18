<?php


namespace App\Services\Statistic\Abstractions;


class StatisticContainer implements IContainer
{
    protected $container = [];

    public function addField(string $key, BaseStatisticItem $value)
    {
        $this->container[$this->genKey($key)] = $value;
    }

    public function getField($key): ?BaseStatisticItem
    {
        $arrKey = $this->genKey($key);
        return $this->container[$arrKey] ?? null;
    }

    public function getContainer()
    {
        return $this->container;
    }

    private function genKey($key) : string
    {
        return md5((string)$key);
    }
}