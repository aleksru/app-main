<?php


namespace App\Services\Statistic\DeliveryTime;

use App\Enums\ProductCategoryEnums;
use App\Services\Statistic\QueryRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DeliveryTimeStatisticRepository
{
    protected $queryRepository;

    public function __construct()
    {
        $this->queryRepository = new QueryRepository();
    }

    public function getDoneCount(Carbon $dateFrom, Carbon $dateTo) : Collection
    {
//        $db = DB::table('orders')
//            ->selectRaw('CONCAT(delivery_periods.timeFrom, "-", delivery_periods.timeTo) as delivery_desc')
//            ->join('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id')
//            ->join('realizations', 'orders.id', '=', 'realizations.order_id')
//            ->join('products', 'realizations.product_id', '=', 'products.id')
//            ->join('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id')
//            ->whereBetween('orders.created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
//            ->whereNull('realizations.deleted_at')
//            ->where('products.category', '!=', ProductCategoryEnums::DELIVERY)
//            //->groupBy('delivery_desc')
//            ->where('other_statuses.result', StatusResults::SUCCESS);

        return $this->queryRepository
            ->getBaseSuccessRealizationsOnOrdersQuery($dateFrom, $dateTo)
            ->join('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id')
            ->selectRaw('CONCAT(delivery_periods.timeFrom, "-", delivery_periods.timeTo) as delivery_desc, COUNT(orders.id) AS cnt')
            ->groupBy('delivery_desc')
            ->get();
    }

    public function getMissedCount(Carbon $dateFrom, Carbon $dateTo) : Collection
    {
        return $this->queryRepository
            ->getBaseRefusalRealizationsOnOrdersQuery($dateFrom, $dateTo)
            ->join('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id')
            ->selectRaw('CONCAT(delivery_periods.timeFrom, "-", delivery_periods.timeTo) as delivery_desc, COUNT(orders.id) AS cnt')
            ->groupBy('delivery_desc')
            ->get();
    }

    public function getProfitOnDates(Carbon $dateFrom, Carbon $dateTo)
    {
//        SELECT CONCAT(delivery_periods.timeFrom, "-", delivery_periods.timeTo) as delivery_desc, (SUM(realizations.price) - SUM(realizations.price_opt)) AS profit
//        from `orders`
//        inner join `other_statuses` on `orders`.`stock_status_id` = `other_statuses`.`id`
//        inner join `delivery_periods` on `orders`.`delivery_period_id` = `delivery_periods`.`id`
//        inner join `realizations` on `orders`.`id` = `realizations`.`order_id`
//        inner join `products` on `realizations`.`product_id` = `products`.`id`
//        where `orders`.`created_at` between '2020-04-01' and '2020-05-11' and `products`.`category` != 'DELIVERY' and
//        `realizations`.`deleted_at` is null and `realizations`.`reason_refusal_id` is NULL  AND other_statuses.result = 1
//        group by `delivery_desc`

        return $this->queryRepository
            ->getBaseOrderSalesProfitQuery($dateFrom, $dateTo)
            ->selectRaw('CONCAT(delivery_periods.timeFrom, "-", delivery_periods.timeTo) as delivery_desc')
            ->join('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id')
            ->groupBy('delivery_desc')
            ->get();
    }

    public function getAvgSales(Carbon $dateFrom, Carbon $dateTo)
    {
//            SELECT delivery_desc, AVG(sum_order) AS avg_check, AVG(profit) AS avg_profit FROM
//            (SELECT CONCAT(delivery_periods.timeFrom, "-", delivery_periods.timeTo) as delivery_desc, orders.id, SUM(realizations.price) AS sum_order, SUM(realizations.price) - SUM(realizations.price_opt) AS profit
//                    from `orders`
//                    inner join `other_statuses` on `orders`.`stock_status_id` = `other_statuses`.`id`
//                    inner join `delivery_periods` on `orders`.`delivery_period_id` = `delivery_periods`.`id`
//                    inner join `realizations` on `orders`.`id` = `realizations`.`order_id`
//                    inner join `products` on `realizations`.`product_id` = `products`.`id`
//                    where `orders`.`created_at` between '2020-04-01' and '2020-05-11' and `products`.`category` != 'DELIVERY' and
//                      `realizations`.`deleted_at` is null and `realizations`.`reason_refusal_id` is NULL AND  other_statuses.result = 1
//                    group by `delivery_desc`, orders.id) AS avd_check
//            GROUP BY delivery_desc
        $query = $this->queryRepository
            ->getBaseOrderSalesProfitQuery($dateFrom, $dateTo)
            ->selectRaw('CONCAT(delivery_periods.timeFrom, "-", delivery_periods.timeTo) as delivery_desc, orders.id, SUM(realizations.price) AS sum_order')
            ->join('delivery_periods', 'orders.delivery_period_id', '=', 'delivery_periods.id')
            ->groupBy('delivery_desc', 'orders.id');
        return DB::table(DB::raw("({$query->toSql()}) as sales"))
            ->mergeBindings($query)
            ->selectRaw('delivery_desc, AVG(sum_order) AS avg_check, AVG(profit) AS avg_profit')
            ->groupBy('delivery_desc')
            ->get();
    }

    public function getSumSalesByDates(Carbon $dateFrom, Carbon $dateTo)
    {
        return $this->queryRepository
                ->getBaseOrderSalesQuery($dateFrom, $dateTo)
                ->sum('realizations.price');
    }


    public function getCountSalesByDates(Carbon $dateFrom, Carbon $dateTo)
    {
        return $this->queryRepository
            ->getBaseSuccessRealizationsOnOrdersQuery($dateFrom, $dateTo)
            ->count();
    }
}
