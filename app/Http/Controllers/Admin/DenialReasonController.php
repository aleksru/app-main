<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DenialReasonRequest;
use App\Models\DenialReason;

class DenialReasonController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.denial_reasons.form', ['denialReasons' => DenialReason::all()]);
    }

    /**
     * @param DenialReasonRequest $denialReasonRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DenialReasonRequest $denialReasonRequest)
    {
        DenialReason::create($denialReasonRequest->validated());

        return redirect()->route('admin.denial-reasons.index')->with(['success' => 'Успешно создано!']);
    }

    /**
     * @param DenialReason $denialReason
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DenialReason $denialReason)
    {
        $denialReason->delete();

        return response()->json(['message' => 'Удалено']);
    }
}