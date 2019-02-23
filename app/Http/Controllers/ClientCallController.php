<?php

namespace App\Http\Controllers;


use App\ClientCall;
use App\Enums\MangoCallEnums;
use Carbon\Carbon;

class ClientCallController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.calls.index');
    }

    /**
     * @return string
     */
    public function datatable()
    {
        $missedCalls = ClientCall::query()
            ->where('status_call', '=', MangoCallEnums::CALL_RESULT_MISSED)
            ->where('created_at', '>=', Carbon::today())
            ->distinct()
            ->get();

        $missedCalls = $missedCalls->reject(function($value, $key) {
            return ! ClientCall::where('from_number', $value->from_number)->where('created_at', '>', $value->created_at)->get()->isEmpty();
        });

        $missedCalls = $missedCalls->each(function($value, $key) {
            $value->append('clientName');
            $value->append('storeName');
        });

        return datatables()->of($missedCalls)->make(true);
    }
}
