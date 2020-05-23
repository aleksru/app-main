<?php


namespace App\Services\Statistic\Suppliers;


use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;

class SuppliersStatisticRepository extends GeneralStatisticRepository
{

    protected function buildJoin(Builder $builder): Builder
    {
        $isJoinRealizations = false;
        foreach ($builder->joins as $join){
            if($join->table === 'realizations'){
                $isJoinRealizations = true;
            }

        }
        if( ! $isJoinRealizations ){
            $builder->join('realizations', 'orders.id', '=', 'realizations.order_id');
        }

        return $builder->join('suppliers', 'realizations.supplier_id', '=', 'suppliers.id');
    }

    protected function getTableName(): string
    {
        return 'suppliers';
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