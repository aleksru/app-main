<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PriceTypeRequest;
use App\PriceType;

class PriceTypeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.price_types.form');
    }

    /**
     * @param PriceTypeRequest $priceTypeRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PriceTypeRequest $priceTypeRequest)
    {
        PriceType::create($priceTypeRequest->validated());

        return redirect()->route('admin.price-types.index')->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param OrderStatus $orderStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OrderStatus $orderStatus)
    {
        $orderStatus->delete();

        return response()->json(['message' => 'Прайс-лист удален']);
    }

}