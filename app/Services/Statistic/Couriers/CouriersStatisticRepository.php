<?php


namespace App\Services\Statistic\Couriers;


use App\Services\Statistic\Abstractions\GeneralStatisticRepository;
use Illuminate\Database\Query\Builder;

class CouriersStatisticRepository extends GeneralStatisticRepository
{
    protected $totalCourierSalesData;

    protected function buildJoin(Builder $builder): Builder
    {
        return $builder->join('couriers', 'orders.courier_id', '=', 'couriers.id');
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

    public function getCourierPayment()
    {
       if($this->totalCourierSalesData === null){
           $this->getCourierPaymentSales();
       }

       return $this->totalCourierSalesData;
    }

    public function getTotalPriceSale()
    {
        if($this->totalCourierSalesData === null){
            $this->getCourierPaymentSales();
        }

        return $this->totalCourierSalesData;
    }

    public function getTotalPriceOpt()
    {
        if($this->totalCourierSalesData === null){
            $this->getCourierPaymentSales();
        }

        return $this->totalCourierSalesData;
    }

    protected function getCourierPaymentSales()
    {
        $query = $this->buildJoin($this->queryRepository->getBaseOrderSalesQuery($this->dateFrom, $this->dateTo));
        $this->totalCourierSalesData = $query->selectRaw($this->getSelection() .
            ', SUM(orders.courier_payment) AS ' . CourierDBContract::SUM_COURIER_PAYMENT .
            ', SUM(realizations.price) AS ' . CourierDBContract::TOTAL_PRICE_SALE .
            ', SUM(realizations.price_opt) ' . CourierDBContract::TOTAL_PRICE_OPT)
            ->groupBy($this->getGroupBy())
            ->get();
    }
}