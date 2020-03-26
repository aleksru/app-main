<?php


namespace App\Services\Docs\Client;

use Carbon\Carbon;

class VoucherProducts extends Voucher
{
    protected function renderView(): string
    {
        return $this->renderedVoucher = view('docs.client.voucher_cust',
                ['voucherData' => $this->order->voucherDataFactory()])
                ->render();
    }

    protected function getFileName(): string
    {
        return $this->order->id . '_products_' . Carbon::now()->getTimestamp() . '.pdf';
    }
}