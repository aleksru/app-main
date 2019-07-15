<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport;


use App\Models\OrderStatus;
use App\Order;

class Sheet2 extends BaseFullReport
{
    /**
     * Data sheet 2
     *
     * @var array
     */
    protected $data = [
        '[statistic.day.date]' => [],
        '[statistic.day.count_orders]' => [],
        '[statistic.day.count_new_orders]' => [],
        '[statistic.day.count_confirm_orders]' => [],
        '[statistic.day.denial_orders]' => [],
        '[statistic.day.callbacks]' => [],
        '[statistic.day.missed_call]' => [],
        '[statistic.day.spam]' => [],
        '[statistic.day.sum_products]' => [],
        '[statistic.day.sum_others]' => [],
        '[statistic.day.sum_main]' => [],
        '[statistic.day.approved_main]' => []
    ];

    /**
     * @return array
     */
    public function prepareDataSheet() : array
    {
        $dates = [];
        $dateStart = clone $this->dateStart;
        do{
            $dates[$dateStart->toDateString()] = collect([]);
            $dateStart->addDay();
        }while($dateStart <= $this->dateEnd);

        $orders = Order::with('realizations.product')->whereDate('created_at', '>=', $this->dateStart->toDateString())
            ->whereDate('created_at', '<=', $this->dateEnd->toDateString())->get();

        $orders->each(function ($item) use (&$dates) {
            $dates[$item->created_at->toDateString()]->push($item);
        });

        $ordersCount = $orders->count();

        foreach ($dates as $date => $value) {
            $this->data['[statistic.day.date]'][]  = $date;
            $this->data['[statistic.day.count_orders]'][] = $value->count();
            $this->data['[statistic.day.count_new_orders]'][] = $this->calcStatuses($value, OrderStatus::STATUS_NEW_PREFIX);
            $this->data['[statistic.day.count_confirm_orders]'][] = $this->calcStatuses($value, OrderStatus::STATUS_CONFIRM_PREFIX);
            $this->data['[statistic.day.denial_orders]'][] = $this->calcStatuses($value, OrderStatus::STATUS_DENIAL_PREFIX);
            $this->data['[statistic.day.callbacks]'][] = $this->calcStatuses($value, OrderStatus::STATUS_CALLBACK_PREFIX);
            $this->data['[statistic.day.missed_call]'][] = $this->calcStatuses($value, OrderStatus::STATUS_MISSED_PREFIX);
            $this->data['[statistic.day.spam]'][] = $this->calcStatuses($value, OrderStatus::STATUS_SPAM_PREFIX);
            $dataSales = $this->calcSales($value);
            $this->data['[statistic.day.sum_products]'][] = number_format($dataSales['main_product_sum'], 2, '.', ' ');
            $this->data['[statistic.day.sum_others]'][] = number_format($dataSales['main_other_sum'], 2, '.', ' ');
            $this->data['[statistic.day.sum_main]'][] = number_format($dataSales['main_check'], 2, '.', ' ');
            $this->data['[statistic.day.approved_main]'][] = $ordersCount > 0 ? round($value->count() * 100 / $ordersCount, 1) . '%' : 0;
        }

        return $this->data;
    }
}