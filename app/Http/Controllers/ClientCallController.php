<?php

namespace App\Http\Controllers;


use App\ClientCall;
use App\Enums\MangoCallEnums;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientCallController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view', ClientCall::class);

        return view('front.calls.index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function datatable(Request $request)
    {
        $requestParams = $request->get('columns');
        $searchParams = [];
        $toDate = Carbon::today();

        foreach($requestParams as $requestParam) {
            if ($requestParam['search']['value']) {
                $searchParams[$requestParam['data']] = $requestParam['search']['value'];
            }
        }

        if(isset($searchParams['fnm_max'])) {
            $toDate = Carbon::parse($searchParams['fnm_max']);
        }

        $successCallsQuery = ClientCall::query()
            ->selectRaw('from_number as fns, created_at as fnsca')
            ->where('status_call', MangoCallEnums::CALL_RESULT_SUCCESS)
            ->whereDate('created_at', $toDate);


        $missedCallsQuery = ClientCall::query()->with('client:id,name', 'store:id,name')
            ->selectRaw('from_number, MAX(created_at) as fnm_max, MAX(success.fnsca) as max_ca_suc, client_id, store_id')
            ->leftJoinSub($successCallsQuery, 'success', function($join) {
                $join->on('client_calls.from_number', '=', 'success.fns');
            })
            ->where('client_calls.status_call', MangoCallEnums::CALL_RESULT_MISSED)
            ->where('client_calls.type', ClientCall::incomingCall)
            ->whereDate('created_at', $toDate)
            ->groupBy('client_calls.from_number', 'client_calls.client_id', 'client_calls.store_id')
            ->havingRaw('fnm_max > IFNULL(max_ca_suc, 0)');

        return datatables()->of($missedCallsQuery)
                ->editColumn('client_id', function(ClientCall $clientCall){
                    return view('datatable.customer', [
                        'route' => route('clients.show', $clientCall->client_id),
                        'name_customer' => $clientCall->clientName ?? 'Не указано'
                    ]);
                })
                ->editColumn('store_id', function(ClientCall $clientCall){
                    return $clientCall->store ? $clientCall->store->name : '';
                })
                ->filterColumn('store_id', function ($query, $keyword) {
                    if (preg_match('/[0-9]/', $keyword)){
                        return $query->where('store_id', $keyword);
                    }
                })
                ->rawColumns(['client_id'])
                ->make(true);
    }
}
