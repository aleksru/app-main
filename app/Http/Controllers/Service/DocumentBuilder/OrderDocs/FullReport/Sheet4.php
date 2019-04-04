<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs\FullReport;


use App\Models\OrderStatus;
use App\Order;
use App\Product;

class Sheet4 extends BaseFullReport
{
    /**
     * @var array
     */
    protected $data = [
        '[products.name]' => [],
        '[products.count_lid]' => [],
        '[products.callbacks]' => [],
        '[products.missed_calls]' => [],
        '[products.denials]' => [],
        '[products.spam]' => [],
        '[products.price]' => [],
        '[products.sum]' => [],
        '[products.calls]' => [],
        '[products.approved_main]' => [],
        '[products.confirm_calls]' => []
    ];

    /**
     * @return array
     */
    public function prepareDataSheet() : array
    {
        $products = Product::has('realizations')->with(['orders' => function($query){
            $query->whereDate('orders.created_at', '>=', $this->dateStart->toDateString())
                    ->whereDate('orders.created_at', '<=', $this->dateEnd->toDateString());
        }, 'realizations', 'orders.realizations', 'orders.realizations.product:id,type'])->get();

        $productsCount = Order::has('realizations')
                                ->whereDate('orders.created_at', '>=', $this->dateStart->toDateString())
                                ->whereDate('orders.created_at', '<=', $this->dateEnd->toDateString())
                                ->count();

        $products->each(function ($value) use ($productsCount) {
            $this->data['[products.name]'][] = $value->product_name;
            $this->data['[products.count_lid]'][] = $value->orders->count();
            $this->data['[products.confirm_calls]'][] =  $this->calcStatuses($value->orders, OrderStatus::STATUS_CONFIRM_PREFIX);
            $this->data['[products.callbacks]'][] =  $this->calcStatuses($value->orders, OrderStatus::STATUS_CALLBACK_PREFIX);
            $this->data['[products.missed_calls]'][] = $this->calcStatuses($value->orders, OrderStatus::STATUS_MISSED_PREFIX);
            $this->data['[products.denials]'][] = $this->calcStatuses($value->orders, OrderStatus::STATUS_DENIAL_PREFIX);
            $this->data['[products.spam]'][] = $this->calcStatuses($value->orders, OrderStatus::STATUS_SPAM_PREFIX);

            $this->data['[products.price]'][] = round($value->orders->map(function ($order) use ($value){
                return $order->realizations->reject(function($item) use ($value){
                    return $value->id !== $item->product_id;
                })->avg('price');
            })->avg(), 1);
            $this->data['[products.sum]'][] = round($value->orders->map(function ($order) use ($value){
                return $order->realizations->map(function($item) use ($value){
                    return $value->id === $item->product_id ? $item->price * $item->quantity  : 0;
                })->sum();
            })->sum(), 1);
            $this->data['[products.approved_main]'][] = $productsCount > 0 ? round($value->orders->count() * 100 / $productsCount, 1) . '%' : 0;
        });

        return $this->data;
    }
}