<?php


namespace App\Repositories;


use App\ClientCall;
use App\Enums\MangoCallEnums;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CallsRepository
{
    /**
     * @param Carbon $dateFrom
     * @param Carbon|null $dateTo
     * @return Collection
     */
    public function getMissedCallBacksInTime(Carbon $dateFrom, ?Carbon $dateTo = null) : Collection
    {
        $dateTo = ($dateTo == $dateFrom) ? $dateTo->addDay() : $dateTo;

        $outgoing = DB::table('client_calls')->selectRaw('from_number as fn_outgoing, call_create_time as cct_outgoing, 
                                            operator_id as operator_id_outgoing, created_at as created_at_outgoing')
                            ->where('type', '=', ClientCall::outgoingCall)
                            ->whereBetween('created_at', [$dateFrom->toDateString(), $dateTo->toDateString() ?? $dateFrom->toDateString()]);
        $calls = DB::table('client_calls')->selectRaw('from_number, call_create_time, outgoing.operator_id_outgoing,
                                        MIN(outgoing.cct_outgoing - client_calls.call_create_time) as sub')
                        ->joinSub($outgoing, 'outgoing', function ($join) {
                            $join->on('client_calls.from_number', '=', 'outgoing.fn_outgoing')
                                ->on('client_calls.call_create_time', '<', 'outgoing.cct_outgoing')
                                ->on(DB::raw('date(client_calls.created_at)'), '=', DB::raw('date(outgoing.created_at_outgoing)'));
                        })->where('type', '=', ClientCall::incomingCall)
                        ->where('status_call', '=', MangoCallEnums::CALL_RESULT_MISSED)
                        ->whereBetween('created_at', [$dateFrom->toDateString(), $dateTo->toDateString() ?? $dateFrom->toDateString()])
                        ->whereTime('created_at', '>', '08:00:00')
                        ->whereTime('created_at', '<', '23:00:00')
                        ->groupBy('from_number', 'call_create_time', 'outgoing.operator_id_outgoing')
                        ->orderByRaw('from_number, sub')
                        ->get();
        $result = collect();
        $calls->each(function ($item, $key) use (&$result){
            $keyRes = $item->from_number . $item->call_create_time;
            if( $itemRes = $result->get($keyRes) ) {
                $itemRes->sub > $item->sub ? $result[$keyRes] = $item : null;
            }else{
                $result[$keyRes] = $item;
            }
        });

        return $result;
    }

    /**
     * @param Carbon $toDate
     * @param bool $isComplaint
     * @return Collection
     */
    public function getMissedCallsForDate(Carbon $toDate, bool $isComplaint = false) : Collection
    {
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
            ->whereRaw('failed.fca > IFNULL(success.sca, 0)')
            ->orderBy('fca', 'DESC')
            ->get();

        return $sql;
    }

    /**
     * @param Carbon $carbon
     * @param array $phones
     * @return int
     */
    public function getUniquePhonesForDate(Carbon $carbon, array $phones = []) : int
    {
        $calls = DB::table('client_calls')
            ->where('is_first', 1)
            ->whereDate('created_at', '=' ,$carbon);
        if( !empty($phones)){
            $calls->whereIn('from_number', $phones);
        }
        $calls = $calls->count();

        return $calls;
    }
}