<?php


namespace App\Services\Statistic\DeliveryTime;

use App\Enums\ProductCategoryEnums;
use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use App\Services\Statistic\QueryRepository;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DeliveryTimeStatisticRepository extends GeneralStatisticRepository
{

    protected function buildJoin(Builder $builder): Builder
    {
        return $builder->join('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id');
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
