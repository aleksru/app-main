<?php


namespace App\Services\Statistic\Stores;

use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;

class StoresStatisticRepository extends GeneralStatisticRepository
{
    protected function buildJoin(Builder $builder): Builder
    {
        return $builder->join('stores', 'orders.store_id', '=', 'stores.id');
    }

    protected function getTableName(): string
    {
        return 'stores';
    }

    public function getFieldName(): string
    {
        return 'name';
    }

    protected function getGroupByFieldName(): string
    {
        return 'id';
    }
}