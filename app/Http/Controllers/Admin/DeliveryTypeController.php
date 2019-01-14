<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeliveryTypeRequest;
use App\Models\DeliveryType;

class DeliveryTypeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.delivery_type.form', ['deliveryTypes' => DeliveryType::all()]);
    }

    /**
     * @param DeliveryTypeRequest $deliveryTypeRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeliveryTypeRequest $deliveryTypeRequest)
    {
        DeliveryType::create($deliveryTypeRequest->validated());

        return redirect()->route('admin.delivery-types.index')->with(['success' => 'Успешно создано!']);
    }

    /**
     * @param DeliveryType $deliveryType
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeliveryType $deliveryType)
    {
        $deliveryType->delete();

        return response()->json(['message' => 'Удалено']);
    }
}