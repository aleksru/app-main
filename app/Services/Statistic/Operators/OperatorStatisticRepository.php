<?php


namespace App\Services\Statistic\Operators;


use App\Enums\ProductCategoryEnums;
use App\Enums\ProductType;
use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;

class OperatorStatisticRepository extends GeneralStatisticRepository
{
    protected $salesAirPods;

    protected function buildJoin(Builder $builder): Builder
    {
        return $builder->join('operators', 'orders.operator_id', '=', 'operators.id');
    }

    protected function getTableName(): string
    {
        return 'operators';
    }

    public function getFieldName(): string
    {
        return 'name';
    }

    protected function getGroupByFieldName(): string
    {
        return 'id';
    }

    public function getTotalSumSalesAccessory()
    {
        $query = $this->buildJoin($this->queryRepository->getBaseOrderSalesQuery($this->dateFrom, $this->dateTo));
        return $query->selectRaw($this->getSelection() .
            ', SUM(realizations.price) AS ' . OperatorsDBContract::SUM_SALES_ACC)
            ->where('products.type', ProductType::TYPE_ACCESSORY)
            ->groupBy($this->getGroupBy())
            ->get();
    }

    public function getCountSaleAirPods()
    {
        if( $this->salesAirPods === null ){
            $this->getSalesAirpods();
        }

        return $this->salesAirPods;
    }

    public function getSumSaleAirPods()
    {
        if( $this->salesAirPods === null ){
            $this->getSalesAirpods();
        }

        return $this->salesAirPods;
    }

    protected function getSalesAirpods()
    {
        $query = $this->buildJoin($this->queryRepository->getBaseOrderSalesQuery($this->dateFrom, $this->dateTo));
        $this->salesAirPods = $query->selectRaw($this->getSelection() .
            ', COUNT(*) AS ' . OperatorsDBContract::COUNT_SALES_AIRPODS .
            ', SUM(realizations.price) AS ' . OperatorsDBContract::SUM_SALES_AIRPODS)
            ->where('products.category', ProductCategoryEnums::HEADPHONES)
            ->where('products.product_name', 'LIKE', '%airpods%')
            ->groupBy($this->getGroupBy())
            ->get();
    }
}