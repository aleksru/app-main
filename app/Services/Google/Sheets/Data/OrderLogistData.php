<?php


namespace App\Services\Google\Sheets\Data;


use App\Order;
use Carbon\Carbon;

class OrderLogistData
{
    /**
     * @var Order
     */
    private $order;

    /**
     * OrderLogistData constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return array
     */
    public function prepareData() : array
    {
        $rows = [];
        $this->order->load(
            'status',
            'store',
            'client',
            'client.additionalPhones',
            'courier',
            'metro',
            'deliveryPeriod',
            'deliveryType',
            'operator',
            'realizations.product');
        $iteration = 0;
        $realizations = $this->order->realizations->sortBy('product_type');
        foreach ($realizations as $product) {
            $rows[$iteration]['nodata'] = '-';
            $rows[$iteration]['date'] = date("d.m.Y", strtotime($this->order->created_at));
            $rows[$iteration]['operator'] = $this->order->operator ? $this->order->operator->name : '';
            $rows[$iteration]['store'] = $this->order->store ? $this->order->store->name : '';
            $rows[$iteration]['order'] = $this->order->id;
            $rows[$iteration]['real_denied'] = '';
            $rows[$iteration]['type'] = $this->order->comment ?? '';
            $rows[$iteration]['comment_logist'] = '';
            $rows[$iteration]['status'] = $this->order->status ? $this->order->status->status : '';
            $rows[$iteration]['client_name'] = $this->order->client ? $this->order->client->name ?? '' : '';
            $rows[$iteration]['delivery_time'] = ($this->order->date_delivery ? $this->order->date_delivery->format('d.m.Y') : '') .
                                                    ' ' . ($this->order->deliveryPeriod ? $this->order->deliveryPeriod->period_full : '');
            $rows[$iteration]['address'] = ($this->order->metro ? 'м.' . $this->order->metro->name . ',' : '') .
                                            ' ' . ($this->order->fullAddress ?? '');
            $rows[$iteration]['client_phone'] = $this->order->client ? implode(', ', $this->order->client->allPhones->toArray()) : '';
            $rows[$iteration]['name'] = $product->product->product_name ?? '';
            $rows[$iteration]['imei'] = $product->imei ?? '';
            $rows[$iteration]['quantity'] = $product->quantity ?? '';
            $rows[$iteration]['price_opt'] = (int)$product->price_opt ?? '';
            $rows[$iteration]['price'] = (int)$product->price ?? '';
            $rows[$iteration]['courier_payment'] = (int)($product->courier_payment ?  $product->courier_payment +
                                        ($this->order->deliveryType->price ?? 0) : $this->order->deliveryType->price ?? 0);
            $rows[$iteration]['profit'] = (int)$product->price - (int)$product->price_opt - (int)$product->courier_payment;
            $rows[$iteration]['courier_name'] = $this->order->courier->name ?? '';
            $rows[$iteration]['supplier'] = $product->supplier ? $product->supplier->name : '';
            $rows[$iteration]['send_date'] = Carbon::now()->toDateTimeString();
            $iteration++;
        }

        return $rows;
    }
}