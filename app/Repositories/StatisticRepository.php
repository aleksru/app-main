<?php


namespace App\Repositories;

use App\Enums\ProductCategoryEnums;
use App\Enums\ProductType;
use App\Enums\StatusResults;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticRepository
{
   public function getStatCountSalesByDate(Carbon $dateFrom, Carbon $dateTo, int $results = null)
   {
        $query = DB::table('orders')
                    ->selectRaw('DATE_FORMAT(orders.created_at,\'%Y-%m-%d\') as created_day, COUNT(*) as count_sales')
                    ->whereBetween('orders.created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
                    ->join('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id')
                    ->whereNotNull('other_statuses.result')
                    ->groupBy('created_day');
        if($results !== null){
            $query->where('other_statuses.result', $results);
        }

        return $query->get();
   }

   public function getSalesCategoriesByDate(Carbon $dateFrom, Carbon $dateTo, array $categories = [])
   {
       $query = DB::table('orders')
           ->selectRaw('products.category as product_category, COUNT(*) as count_sales')
           ->join('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id')
           ->join('realizations', 'orders.id', '=', 'realizations.order_id')
           ->join('products', 'realizations.product_id', '=', 'products.id')
           ->where('other_statuses.result', StatusResults::SUCCESS)
           ->whereBetween('orders.created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
           ->whereNull('realizations.reason_refusal_id')
           ->whereNull('realizations.deleted_at')
           ->whereNotNull('products.category')
           ->orderBy('count_sales', 'DESC')
           ->groupBy('products.category');
       if( ! empty($categories) ){
           $query->whereIn('products.category', $categories);
       }

       return $query->get();
   }

   public function getTopSalesProducts(Carbon $dateFrom, Carbon $dateTo, int $count = 20)
   {
       return DB::table('orders')
               ->selectRaw('products.product_name as product, COUNT(*) as count_sales')
               ->join('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id')
               ->join('realizations', 'orders.id', '=', 'realizations.order_id')
               ->join('products', 'realizations.product_id', '=', 'products.id')
               ->where('other_statuses.result', StatusResults::SUCCESS)
               ->whereBetween('orders.created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
               ->whereNull('realizations.reason_refusal_id')
               ->whereNull('realizations.deleted_at')
               ->where('products.type', ProductType::TYPE_PRODUCT)
               ->orderBy('count_sales', 'DESC')
               ->groupBy('products.product_name')
               ->limit($count)
               ->get();
   }
}