<?php


namespace App\Services\Statistic\PickUp;


use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;

class PickUpStatisticRepository extends GeneralStatisticRepository
{

    protected function buildJoin(Builder $builder): Builder
    {
        return $builder->join('delivery_types', 'orders.delivery_type_id', '=', 'delivery_types.id')
            ->join('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id')
            ->where('delivery_types.type', '=', 'Самовывоз');
    }

    protected function getTableName(): string
    {
        return 'delivery_periods';
    }

    public function getFieldName(): string
    {
        return 'delivery_desc';
    }

    protected function getGroupByFieldName(): string
    {
        return 'delivery_desc';
    }

    protected function getSelection(): string
    {
        return 'CONCAT(delivery_periods.timeFrom, "-", delivery_periods.timeTo) as delivery_desc';
    }

    protected function getGroupBy(): string
    {
        return 'delivery_desc';
    }
}