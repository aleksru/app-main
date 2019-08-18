<?php


namespace App\Http\Controllers;


use App\Models\OrderStatus;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $operatorStatuses = OrderStatus::select('id', 'status as name')->get()->toArray();

        return view('front.statuses.index', compact('operatorStatuses'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function massChange(Request $request)
    {
        if ($request->get('operator_status_id')) {
            DB::table('orders')->whereIn('id', $request->get('order_ids'))
                        ->update(['status_id' => $request->get('operator_status_id')]);
        }

        return response()->json(['message' => 'Успешно обновлено!']);
    }
}