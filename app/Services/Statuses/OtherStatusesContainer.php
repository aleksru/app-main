<?php


namespace App\Services\Statuses;


use App\Models\OtherStatus;
use Illuminate\Support\Collection;

class OtherStatusesContainer
{
    protected $container = [];

    public function add(OtherStatus $otherStatus)
    {
        $this->container[$this->makeKey($otherStatus->name)] = $otherStatus;
    }

    public function addAll(Collection $collection)
    {
        foreach ($collection as $item){
            $this->add($item);
        }
    }

    public function getByName(string $name): ?OtherStatus
    {
        return $this->container[$this->makeKey($name)] ?? null;
    }

    private function makeKey(string $key): string
    {
        return md5(mb_strtolower(str_replace(' ', '', $key)));
    }
}
