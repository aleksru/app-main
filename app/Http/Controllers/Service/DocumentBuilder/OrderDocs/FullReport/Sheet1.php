<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport;


use App\Models\Operator;
use App\Models\OrderStatus;

class Sheet1 extends BaseFullReport
{
    /**
     * Data sheet 1
     *
     * @var array
     */
    protected $data = [
        '[operators]' => [],
        '[operators.count_lid]' => [],
        '[operators.confirmation_1]' => [],
        '[operators.callbacks]' => [],
        '[operators.missedcall]' => [],
        '[operators.denial]' => [],
        '[operators.garbage]' => [],
        '[operators.avg_check]' => [],
        '[operators.main_check]' => [],
        '[operators.main_product_sum]' => [],
        '[operators.main_other_sum]' => [],
        '[operator.count_calls]' => [],
        '[operator.count_time_calls]' => [],
        '[operator.count_avg_calls]' => []
    ];

    /**
     * @return array
     */
    public function prepareDataSheet() : array
    {
        $operators = Operator::with([
            'orders.realizations', 'orders.realizations.product:id,type',
            'orders' => function($query){
                $query->whereDate('created_at', '>=', $this->dateStart->toDateString())
                    ->whereDate('created_at', '<=', $this->dateEnd->toDateString());
            },
            'calls' => function($query){
                $query->whereDate('created_at', '>=', $this->dateStart->toDateString())
                    ->whereDate('created_at', '<=', $this->dateEnd->toDateString());
            }
        ])->get();

        $operators->each(function($value){
            $this->data['[operators]'][]= $value->name;
            $this->data['[operators.count_lid]'][]= $value->orders->count();
            $this->data['[operators.confirmation_1]'][] = $this->calcStatuses($value->orders, OrderStatus::STATUS_CONFIRM_PREFIX);
            $this->data['[operators.callbacks]'][] = $this->calcStatuses($value->orders, OrderStatus::STATUS_CALLBACK_PREFIX);
            $this->data['[operators.missedcall]'][] = $this->calcStatuses($value->orders, OrderStatus::STATUS_MISSED_PREFIX);
            $this->data['[operators.denial]'][] = $this->calcStatuses($value->orders, OrderStatus::STATUS_DENIAL_PREFIX);
            $this->data['[operators.garbage]'][] = $this->calcStatuses($value->orders, OrderStatus::STATUS_SPAM_PREFIX);
            $this->data['[operator.count_calls]'][] = $value->calls->count();
            $this->data['[operator.count_time_calls]'][] = $value->calls->map(function($item) {
                return round(($item->call_end_time - $item->call_create_time * 10) / 60, 1);
            })->sum();
            $this->data['[operator.count_avg_calls]'][] = round($value->calls->map(function($item) {
                return ($item->call_end_time - $item->call_create_time * 10) / 60;
            })->avg(), 1);
            $dataSales = $this->calcSales($value->orders);
            $this->data['[operators.avg_check]'][] = $dataSales['avg_check'];
            $this->data['[operators.main_check]'][] = $dataSales['main_check'];
            $this->data['[operators.main_product_sum]'][] = $dataSales['main_product_sum'];
            $this->data['[operators.main_other_sum]'][] = $dataSales['main_other_sum'];
        });

        return $this->data;
    }
}