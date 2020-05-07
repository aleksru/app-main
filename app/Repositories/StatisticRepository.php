<?php


namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticRepository
{
   public function getStatCountSalesByDate(Carbon $dateFrom, Carbon $dateTo = null, int $results = null)
   {
        if( ! $dateTo ){
           $dateTo = clone $dateFrom;
           $dateTo->addDay();
        }
        $query = DB::table('orders')->selectRaw('DATE_FORMAT(orders.created_at,\'%Y-%m-%d\') as created_day, COUNT(*) as count_sales')
                    ->whereBetween('orders.created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
                    ->join('other_statuses', 'orders.stock_status_id', '=', 'other_statuses.id')
                    ->whereNotNull('other_statuses.result')
                    ->groupBy('created_day');
        if($results !== null){
            $query->where('other_statuses.result', $results);
        }

        return $query->get();
   }
}