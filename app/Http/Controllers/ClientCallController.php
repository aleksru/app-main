<?php

namespace App\Http\Controllers;


use App\ClientCall;
use App\Enums\MangoCallEnums;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if(isset($searchParams['fca'])) {
            $toDate = Carbon::parse($searchParams['fca']);
        }

        $successCallsQuery = ClientCall::query()
            ->selectRaw('from_number as s_from_number, max(created_at) as sca')
            ->where('status_call', MangoCallEnums::CALL_RESULT_SUCCESS)
            ->whereDate('created_at', $toDate)
            ->groupBy('from_number');

        $sql = DB::query()
            ->selectRaw('failed.from_number, failed.client_id, stores.`name` as store_name, clients.`name` as client_name, failed.fca')
            ->fromSub(function ($query) use ($toDate){
                $query->from('client_calls')
                    ->selectRaw('from_number, max(client_id) as client_id, max(store_id) as store_id, max(created_at) as fca')
                    ->where('status_call', MangoCallEnums::CALL_RESULT_MISSED)
                    ->where('type', ClientCall::incomingCall)
                    ->whereDate('created_at', $toDate)
                    ->groupBy('from_number');
            }, 'failed')
            ->leftJoinSub($successCallsQuery, 'success', function($join) {
                $join->on('failed.from_number', '=', 'success.s_from_number');
            })
            ->leftJoin('clients', 'failed.client_id', '=', 'clients.id')
            ->leftJoin('stores', 'failed.store_id', '=', 'stores.id')
            ->whereRaw('failed.fca > IFNULL(success.sca, 0)');


        return datatables()->of($sql)
                ->editColumn('client_id', function($clientCall){
                    return view('datatable.customer', [
                        'route' => route('clients.show', $clientCall->client_id),
                        'name_customer' => $clientCall->client_name ?? 'Не указано'
                    ]);
                })
                ->editColumn('store_id', function($clientCall){
                    return $clientCall->store_name ?? 'Не найден';
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
