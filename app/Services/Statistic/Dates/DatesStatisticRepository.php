<?php


namespace App\Services\Statistic\Dates;


use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;

class DatesStatisticRepository extends GeneralStatisticRepository
{
    protected function buildJoin(Builder $builder): Builder
    {
        return $builder;
    }

    protected function getTableName(): string
    {
        return 'orders';
    }

    public function getFieldName(): string
    {
        return 'date_delivery';
    }

    protected function getGroupByFieldName(): string
    {
        return 'date_delivery';
    }
}