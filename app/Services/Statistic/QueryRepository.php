<?php


namespace App\Services\Statistic;


use App\Enums\ProductCategoryEnums;
use App\Enums\StatusResults;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class QueryRepository
{
    public function getBaseStatisticOnOrdersQuery(Carbon $dateFrom, Carbon $dateTo): Builder
    {
        return DB::table('orders')
            ->join('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id')
            ->whereBetween('orders.date_delivery', [$dateFrom->toDateString(), $dateTo->toDateString()]);
    }

    public function getBaseSuccessRealizationsOnOrdersQuery(Carbon $dateFrom, Carbon $dateTo): Builder
    {
        return $this->getBaseStatisticOnOrdersQuery($dateFrom, $dateTo)
            ->where('other_statuses.result', StatusResults::SUCCESS);
    }

    public function getBaseRefusalRealizationsOnOrdersQuery(Carbon $dateFrom, Carbon $dateTo): Builder
    {
        return $this->getBaseStatisticOnOrdersQuery($dateFrom, $dateTo)
            ->where('other_statuses.result', StatusResults::REFUSAL);
    }

    public function getBaseOrderSalesQuery(Carbon $dateFrom, Carbon $dateTo): Builder
    {
        return $this->buildBaseRealizations($this->getBaseSuccessRealizationsOnOrdersQuery($dateFrom, $dateTo));
    }

    public function getBaseOrderAllSalesQuery(Carbon $dateFrom, Carbon $dateTo): Builder
    {
        return $this->buildBaseAllRealizations($this->getBaseStatisticOnOrdersQuery($dateFrom, $dateTo));
    }

    public function getBaseOrderSalesProfitQuery(Carbon $dateFrom, Carbon $dateTo): Builder
    {
        return $this->getBaseOrderSalesQuery($dateFrom, $dateTo)
            ->selectRaw('(SUM(realizations.price) - SUM(realizations.price_opt)) AS profit');
    }

    protected function buildBaseRealizations(Builder $builder): Builder
    {
        return $builder
                ->join('realizations', 'orders.id', '=', 'realizations.order_id')
                ->join('products', 'realizations.product_id', '=', 'products.id')
                ->whereRaw('IFNULL(products.category, "Без категории") != "' . ProductCategoryEnums::DELIVERY . '"')
                ->whereNull('realizations.deleted_at')
                ->whereNull('realizations.reason_refusal_id');
    }

    protected function buildBaseAllRealizations(Builder $builder): Builder
    {
        return $builder
            ->join('realizations', 'orders.id', '=', 'realizations.order_id')
            ->join('products', 'realizations.product_id', '=', 'products.id')
            ->whereRaw('IFNULL(products.category, "Без категории") != "' . ProductCategoryEnums::DELIVERY . '"')
            ->whereNull('realizations.deleted_at');
    }
}