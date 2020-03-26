<?php


namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Services\Docs\Client\Voucher;
use App\Order;
use App\Services\Docs\Client\VoucherProducts;
use App\Services\Docs\Client\VoucherDelivery;
use Illuminate\Support\Facades\App;

class VoucherController extends Controller
{
    public function get()
    {

    }

    public function orderInvoice(Order $order)
    {
        /**
         * @var Voucher $voucher;
         */
        $order->load('realizations.product');
        $voucher = App::make(VoucherProducts::class, ['order' => $order]);
        return $voucher->download();
    }

    public function orderDelivery(Order $order)
    {
        /**
         * @var Voucher $voucher;
         */
        $order->load('realizations.product');
        $voucher = App::make(VoucherDelivery::class, ['order' => $order]);
        return $voucher->download();
    }
}
