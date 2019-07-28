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
}