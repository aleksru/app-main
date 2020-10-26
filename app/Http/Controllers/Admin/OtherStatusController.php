<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OtherStatusEnums;
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
        $realizationStatuses = OtherStatus::typeRealizationStatuses()->get();
        $activeNav = \request()->get('type') !== NULL ? \request()->get('type') : OtherStatusEnums::SUBSTATUS_TYPE;

        return view('admin.other_statuses.form',
                    compact(
                        'subStatuses',
                        'stockStatuses',
                        'logisticStatuses',
                        'realizationStatuses',
                        'activeNav'
                    )
        );
    }

    /**
     * @param OtherStatus $otherStatus
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(OtherStatus $otherStatus)
    {
        return view('admin.other_statuses.edit', compact('otherStatus'));
    }

    /**
     * @param OtherStatus $otherStatus
     * @param OtherStatusRequest $otherStatusRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OtherStatus $otherStatus, OtherStatusRequest $otherStatusRequest)
    {
        $otherStatus->update($otherStatusRequest->validated());
        return redirect()->route('admin.other-statuses.edit', compact('otherStatus'))->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param OtherStatusRequest $otherStatusRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OtherStatusRequest $otherStatusRequest)
    {
        OtherStatus::create($otherStatusRequest->validated());

        return redirect()->route('admin.other-statuses.index', ['type' => $otherStatusRequest->get('type')])
                        ->with(['success' => 'Успешно создано!']);
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
