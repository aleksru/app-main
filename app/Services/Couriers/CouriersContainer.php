<?php


namespace App\Services\Couriers;


use App\Models\Courier;
use Illuminate\Support\Collection;

class CouriersContainer
{
    protected $container = [];

    public function add(Courier $courier)
    {
        $this->container[$this->makeKey($courier->name)] = $courier;
    }

    public function addAll(Collection $collection)
    {
        foreach ($collection as $item){
            $this->add($item);
        }
    }

    public function getByName(string $name): ?Courier
    {
        return $this->container[$this->makeKey($name)] ?? null;
    }

    private function makeKey(string $key): string
    {
        return md5(mb_strtolower(str_replace(' ', '', $key)));
    }
}
