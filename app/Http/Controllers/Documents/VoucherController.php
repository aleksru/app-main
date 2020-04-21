<?php


namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Services\Docs\Client\Voucher;
use App\Order;
use App\Services\Docs\Client\VoucherProducts;
use App\Services\Docs\Client\VoucherDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class VoucherController extends Controller
{
    public function get()
    {

    }

    /**
     * @var Voucher $voucher;
     * @param Order $order
     * @param Request $request
     * @return mixed
     */
    public function orderInvoice(Order $order, Request $request)
    {
        $order->load('realizations.product');
        $voucher = App::make(VoucherProducts::class, ['order' => $order]);
        if($request->get('show')){
            return $voucher->stream();
        }
        return $voucher->download();
    }

    /**
     * @var Voucher $voucher;
     * @param Order $order
     * @param Request $request
     * @return mixed
     */
    public function orderDelivery(Order $order, Request $request)
    {
        $order->load('realizations.product');
        $voucher = App::make(VoucherDelivery::class, ['order' => $order]);
        if($request->get('show')){
            return $voucher->stream();
        }
        return $voucher->download();
    }
}
