<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\DeliveryPeriod;
use App\Models\OtherDelivery;
use Illuminate\Http\Request;


class DeliveryPeriodsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.periods.form', [
            'periods' => DeliveryPeriod::query()->get(),
            'otherPeriods' => OtherDelivery::all(),
        ]);
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

            $modelPeriod->period = $period['period'] ?? '';
            $modelPeriod->timeFrom = $period['timeFrom'] ?? null;
            $modelPeriod->timeTo = $period['timeTo']?? null;
            $modelPeriod->save();
        }

        return response()->json(['periods' => DeliveryPeriod::query()->get(), 'message' => 'Добавлено!']);
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

    /**
     * @param OtherDelivery $otherDelivery
     * @return \Illuminate\Http\JsonResponse
     */
    public function otherDeliveryDestroy(OtherDelivery $otherDelivery)
    {
        $otherDelivery->delete();

        return response()->json(['message' => 'Доп тип удален']);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function otherDeliveryStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        OtherDelivery::create($validatedData);

        return redirect()->back()->with(['success' => 'Успешно добавлено!']);
    }

}