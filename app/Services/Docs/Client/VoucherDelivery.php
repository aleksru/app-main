<?php


namespace App\Services\Docs\Client;


use Carbon\Carbon;

class VoucherDelivery extends Voucher
{
    protected function renderView(): string
    {
        return $this->renderedDelivery = view('docs.client.voucher_delivery',
                ['voucherData' => $this->order->voucherDeliveryDataFactory()])
                ->render();
    }

    protected function getFileName(): string
    {
        return $this->order->id . '_delivery_' . Carbon::now()->getTimestamp() . '.pdf';
    }
}