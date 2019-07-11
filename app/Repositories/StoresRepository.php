<?php


namespace App\Repositories;


use App\Store;
use Illuminate\Database\Eloquent\Collection;

class StoresRepository
{
    /**
     * @return Collection
     */
    public function getStoresToWidget() : Collection
    {
        return Store::active()->whereNotNull('active_at')->whereNotNull('url')->get();
    }

}