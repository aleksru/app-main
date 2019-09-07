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
    public function getIdsMissedCallsForDate(Carbon $toDate, bool $isComplaint = false) : Collection
    {
        //SELECT m_id from
        //    (select from_number, max(id) as m_id
        //		from `client_calls` where `status_call` = 0 and `type` = 'INCOMING' and date(`created_at`) = '2019-07-25' group by `from_number`) as missed
        //left join
        //    (select from_number as from_number, max(id) as s_id from `client_calls` where `status_call` = 1 and date(`created_at`) = '2019-07-25' group by `from_number`) as success
        //on `missed`.`from_number` = `success`.`from_number`
        //
        //WHERE m_id > IFNULL(s_id, 0)

        $successCallsQuery = DB::table('client_calls')
            ->selectRaw('from_number as from_number, max(id) as s_id')
            ->where('status_call', MangoCallEnums::CALL_RESULT_SUCCESS)
            ->whereDate('created_at', $toDate)
            ->groupBy('from_number');

        $sql = DB::query()
            ->selectRaw('m_id')
            ->fromSub(function ($query) use ($toDate, $isComplaint){
                $query->from('client_calls')
                    ->selectRaw('from_number, max(id) as m_id')
                    ->where('status_call', MangoCallEnums::CALL_RESULT_MISSED)
                    ->whereRaw('IFNULL(extension, 0) ' . ($isComplaint ? '= ' : '!= ') .  MangoCallEnums::CALL_GROUP_COMPLAINT)
                    ->where('type', ClientCall::incomingCall)
                    ->whereDate('created_at', $toDate)
                    ->groupBy('from_number');
            }, 'missed')
            ->leftJoinSub($successCallsQuery, 'success', function($join) {
                $join->on('missed.from_number', '=', 'success.from_number');
            })
            ->whereRaw('m_id > IFNULL(s_id, 0)')
            ->pluck('m_id');

        return $sql;
    }

    /**
     * @param Carbon $carbon
     * @param array $phones
     * @return int
     */
    public function getUniquePhonesForDate(Carbon $carbon, array $phones = []) : int
    {
        //  select count(*) as cnt from `client_calls`
        //	WHERE id IN(
        //        select max(id) as max_id from `client_calls` where date(`created_at`) = '2019-09-01' and `from_number` in ('79168599960', '79608676770', '79773639683', '79865653256') GROUP BY from_number
        //	)
        //	AND is_first = 1

        $subQuery =  DB::table('client_calls')
            ->selectRaw('max(id) as max_id')
            ->whereDate('created_at', '=' ,$carbon)
            ->groupBy('from_number');

        $calls = DB::table('client_calls');
        if( !empty($phones)){
            $subQuery->whereIn('from_number', $phones);
            $calls ->whereIn('id', $subQuery);
        }

        $calls = $calls->where('is_first', 1)->count();

        return $calls;
    }

    /**
     * @param string $number
     * @return int
     */
    public function getCountCallsFromNumber(string $number) : int
    {
        return DB::table('client_calls')->where('from_number', $number)->count();
    }
}