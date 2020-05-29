<?php


namespace App\Services\Statistic\ProductStores;


use App\Enums\ProductType;
use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ProductStoresStatisticRepository extends GeneralStatisticRepository
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

        return $builder->join('stores', 'orders.store_id', '=', 'stores.id')
            ->where('products.type', ProductType::TYPE_PRODUCT);
    }

    protected function getTableName(): string
    {
        return 'products';
    }

    public function getFieldName(): string
    {
        return 'field_name';
    }

    protected function getGroupByFieldName(): string
    {
        return 'id';
    }

    protected function getSelection(): string
    {
        return 'products.product_name, stores.name, CONCAT(products.product_name,\'#\' ,stores.name) AS field_name';
    }

    protected function getGroupBy()
    {
        return [$this->getTableName() . '.' . $this->getGroupByFieldName(), 'orders.store_id'];
    }
}