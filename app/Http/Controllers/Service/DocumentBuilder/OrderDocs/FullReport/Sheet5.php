<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport;


use App\Models\OrderStatus;
use App\Order;
use App\Store;

class Sheet5 extends BaseFullReport
{
    /**
     * @var array
     */
    protected $data = [
        '[resources.name]' => [],
        '[resources.count_lid]' => [],
        '[resources.callbacks]' => [],
        '[resources.missed_calls]' => [],
        '[resources.denials]' => [],
        '[resources.spam]' => [],
        '[resources.avg_check]' => [],
        '[resources.product_sum]' => [],
        '[resources.other_sum]' => [],
        '[resources.main_sum]' => [],
        '[resources.approved_main]' => [],
        '[resources.confirm_calls]' => []

    ];

    /**
     * @return array
     */
    public function prepareDataSheet() : array
    {
        $stores = Store::with(['orders' => function($query){
            $query->whereDate('orders.created_at', '>=', $this->dateStart->toDateString())
                    ->whereDate('orders.created_at', '<=', $this->dateEnd->toDateString());
        }, 'orders.realizations.product'])->get();

        $ordersCount = Order::has('store')
                                ->whereDate('orders.created_at', '>=', $this->dateStart->toDateString())
                                ->whereDate('orders.created_at', '<=', $this->dateEnd->toDateString())
                                ->count();

        $stores->each(function ($store) use ($ordersCount) {
            $this->data['[resources.name]'][] = $store->name;
            $this->data['[resources.count_lid]'][] = $store->orders->count();
            $this->data['[resources.confirm_calls]'][] = $this->calcStatuses($store->orders, OrderStatus::STATUS_CONFIRM_PREFIX);
            $this->data['[resources.callbacks]'][] = $this->calcStatuses($store->orders, OrderStatus::STATUS_CALLBACK_PREFIX);
            $this->data['[resources.missed_calls]'][] = $this->calcStatuses($store->orders, OrderStatus::STATUS_MISSED_PREFIX);
            $this->data['[resources.denials]'][] = $this->calcStatuses($store->orders, OrderStatus::STATUS_DENIAL_PREFIX);
            $this->data['[resources.spam]'][] = $this->calcStatuses($store->orders, OrderStatus::STATUS_SPAM_PREFIX);
            $sales = $this->calcSales($store->orders);
            $this->data['[resources.avg_check]'][] = $sales['avg_check'];
            $this->data['[resources.product_sum]'][] = $sales['main_product_sum'];
            $this->data['[resources.other_sum]'][] = $sales['main_other_sum'];
            $this->data['[resources.main_sum]'][] = $sales['main_check'];
            $this->data['[resources.approved_main]'][] = $ordersCount > 0 ? round($store->orders->count() * 100 / $ordersCount, 1) . '%' : 0;
        });

        return $this->data;
    }
}