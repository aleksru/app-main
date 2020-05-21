<?php


namespace App\Services\Statistic\ProductCategories;

use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;


class ProductCategoriesStatisticRepository extends GeneralStatisticRepository
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

        return $builder;
    }

    protected function getTableName(): string
    {
        return 'products';
    }

    public function getFieldName(): string
    {
        return 'category';
    }

    protected function getGroupByFieldName(): string
    {
        return 'category';
    }

    protected function getSelection(): string
    {
        return 'IFNULL(products.category, "Без категории") AS ' . $this->getFieldName();
    }
}