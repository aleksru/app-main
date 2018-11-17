<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\DeliveryPeriod;
use Illuminate\Http\Request;


class DeliveryPeriodsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.periods.form', ['periods' => DeliveryPeriod::select('id', 'period')->get()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        foreach($request->all() as $period) {
            $modelPeriod = DeliveryPeriod::find($period['id']);

            if(!$modelPeriod){
                $modelPeriod = new DeliveryPeriod;
            }

            $modelPeriod->period = $period['period'];
            $modelPeriod->save();
        }

        return response()->json(['periods' => DeliveryPeriod::select('id', 'period')->get(), 'message' => 'Добавлено!']);
    }

    /**
     * @param DeliveryPeriod $delivery_period
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeliveryPeriod $delivery_period)
    {
        $delivery_period->delete();

        return response()->json(['message' => 'Период удален']);
    }
}