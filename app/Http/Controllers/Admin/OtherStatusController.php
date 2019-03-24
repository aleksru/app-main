<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OtherStatusRequest;
use App\Models\OtherStatus;
use Illuminate\Http\Request;

class OtherStatusController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $subStatuses = OtherStatus::typeSubStatuses()->get();
        $stockStatuses = OtherStatus::typeStockStatuses()->get();
        $logisticStatuses = OtherStatus::typeLogisticStatuses()->get();

        return view('admin.other_statuses.form', compact('subStatuses', 'stockStatuses', 'logisticStatuses'));
    }

    /**
     * @param OtherStatusRequest $otherStatusRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OtherStatusRequest $otherStatusRequest)
    {
        OtherStatus::create($otherStatusRequest->validated());

        return redirect()->route('admin.other-statuses.index')->with(['success' => 'Успешно создано!']);
    }

    /**
     * @param OtherStatus $otherStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OtherStatus $otherStatus)
    {
        $otherStatus->delete();

        return response()->json(['message' => 'Удалено']);
    }
}
