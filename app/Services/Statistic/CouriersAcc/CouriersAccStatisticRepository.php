<?php

namespace App\Services\Statistic\CouriersAcc;

use App\Enums\ProductType;
use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;

class CouriersAccStatisticRepository extends GeneralStatisticRepository
{
    protected function buildJoin(Builder $builder): Builder
    {
        $isJoinRealizations = false;
        $isJoinProducts = false;

        foreach ($builder->joins as $join){
            if($join->table === 'realizations'){
                $isJoinRealizations = true;
            }
            if($join->table === 'products'){
                $isJoinProducts = true;
            }
        }
        if( ! $isJoinRealizations ){
            $builder->join('realizations', 'orders.id', '=', 'realizations.order_id');
        }
        if( ! $isJoinProducts ){
            $builder->join('products', 'realizations.product_id', '=', 'products.id');
        }

        return $builder->join('couriers', 'orders.courier_id', '=', 'couriers.id')
                ->where('products.type', ProductType::TYPE_ACCESSORY);
    }

    protected function getTableName(): string
    {
        return 'couriers';
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