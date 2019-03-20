<?php


namespace App\Repositories;


use App\Order;
use Illuminate\Support\Facades\DB;

class StatisticRepository
{
    public function getOrdersForMonth()
    {
        return DB::table('orders')->selectRaw('COUNT(*) as count, MONTH(created_at) as month, YEAR(created_at) as year')
                  ->groupBy('month', 'year')
                  ->orderBy('year')
                  ->orderBy('month')
                  ->get();
    }
}