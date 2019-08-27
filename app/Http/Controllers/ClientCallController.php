<?php

namespace App\Http\Controllers;


use App\Client;
use App\ClientCall;
use App\Enums\MangoCallEnums;
use App\Events\ResultCallBack;
use App\Models\Operator;
use App\Services\Mango\Commands\Callback;
use App\Services\Mango\MangoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    public function callback(Operator $operator, Request $request)
    {
        if($operator->extension === null){
            return response()->json(['message' => 'Не назначен внутренний номер в системе. Обратитесь к системному администратору'], 422);
        }
        $callback = new Callback();
        $uuid = Str::uuid();
        $callback->command_id($uuid)
                ->extension($operator->extension)
                ->to_number($request->get('phone'));
        $mangoService = new MangoService();
        $mangoService->callback($callback);
        Log::channel('custom')->error(['ClientCallController', (array)$callback]);

        return response()->json(['command_id' => $uuid]);
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
        $isComplaint = $request->get('isComplaint') == 'true' ? true : false;

        foreach($requestParams as $requestParam) {
            if ($requestParam['search']['value']) {
                $searchParams[$requestParam['data']] = $requestParam['search']['value'];
            }
        }

        if(isset($searchParams['fca'])) {
            $toDate = Carbon::parse($searchParams['fca']);
        }

        $successCallsQuery = ClientCall::query()
            ->selectRaw('from_number as s_from_number, max(call_end_time) as sca')
            ->where('status_call', MangoCallEnums::CALL_RESULT_SUCCESS)
            ->whereDate('created_at', $toDate)
            ->groupBy('from_number');

        $sql = DB::query()
            ->selectRaw('failed.from_number, failed.client_id, stores.`name` as store_name, clients.`name` as client_name, failed.fca')
            ->fromSub(function ($query) use ($toDate, $isComplaint){
                $query->from('client_calls')
                    ->selectRaw('from_number, max(client_id) as client_id, max(store_id) as store_id, max(call_end_time) as fca')
                    ->where('status_call', MangoCallEnums::CALL_RESULT_MISSED)
                    ->where('type', ClientCall::incomingCall)
                    ->whereRaw('IFNULL(extension, 0) ' . ($isComplaint ? '= ' : '!= ') .  MangoCallEnums::CALL_GROUP_COMPLAINT)
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
                ->editColumn('fca', function($clientCall){
                    return Carbon::createFromTimestamp($clientCall->fca)->format('d.m.Y H:i:s');
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
