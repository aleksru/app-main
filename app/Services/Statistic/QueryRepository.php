<?php


namespace App\Services\Statistic;


use App\Enums\ProductCategoryEnums;
use App\Enums\StatusResults;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class QueryRepository
{
    public function getBaseStatisticOnOrdersQuery(Carbon $dateFrom, Carbon $dateTo) : Builder
    {
        return DB::table('orders')
            ->join('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id')
            ->whereBetween('orders.created_at', [$dateFrom->toDateString(), $dateTo->toDateString()]);
    }

    public function getBaseSuccessRealizationsOnOrdersQuery(Carbon $dateFrom, Carbon $dateTo)  : Builder
    {
        return $this->getBaseStatisticOnOrdersQuery($dateFrom, $dateTo)
                ->where('other_statuses.result', StatusResults::SUCCESS);
    }

    public function getBaseRefusalRealizationsOnOrdersQuery(Carbon $dateFrom, Carbon $dateTo)  : Builder
    {
        return $this->getBaseStatisticOnOrdersQuery($dateFrom, $dateTo)
                ->where('other_statuses.result', StatusResults::REFUSAL);
    }

    public function getBaseOrderSalesQuery(Carbon $dateFrom, Carbon $dateTo)  : Builder
    {
        return $this->getBaseSuccessRealizationsOnOrdersQuery($dateFrom, $dateTo)
            ->join('realizations', 'orders.id', '=', 'realizations.order_id')
            ->join('products', 'realizations.product_id', '=', 'products.id')
            ->where('products.category', '!=', ProductCategoryEnums::DELIVERY)
            ->whereNull('realizations.deleted_at')
            ->whereNull('realizations.reason_refusal_id');
    }

    public function getBaseOrderSalesProfitQuery(Carbon $dateFrom, Carbon $dateTo)  : Builder
    {
        return $this->getBaseOrderSalesQuery($dateFrom, $dateTo)
            ->selectRaw('(SUM(realizations.price) - SUM(realizations.price_opt)) AS profit');
    }
}