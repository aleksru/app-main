<?php

namespace App\Models\Queries;

use App\ClientCall;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class OperatorQuery
{
    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Builder
     */
    public function getCountOutgoingsByDates(Carbon $dateFrom, Carbon $dateTo) : Builder
    {
        return DB::table('client_calls')
            ->selectRaw('operator_id, COUNT(*) AS cnt_outgoings')
            ->where('type', ClientCall::outgoingCall)
            ->whereBetween('created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->groupBy('operator_id');
    }

    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Builder
     */
    public function getCountIncomingsByDates(Carbon $dateFrom, Carbon $dateTo) : Builder
    {
        return DB::table('client_calls')
            ->selectRaw('operator_id, COUNT(*) AS cnt_incomings')
            ->where('type', ClientCall::incomingCall)
            ->whereBetween('created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->groupBy('operator_id');
    }

    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Builder
     */
    public function getAvgTalkByDates(Carbon $dateFrom, Carbon $dateTo) : Builder
    {
        $query = $this->getCountIncomingsByDates($dateFrom, $dateTo);
        $query->columns = [];

        return $query->selectRaw('operator_id, AVG(call_talk_time - call_create_time) AS avg_talk')
                    ->where('call_talk_time', '<>', 0);
    }

    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Builder
     */
    public function getCallTimesByDates(Carbon $dateFrom, Carbon $dateTo) : Builder
    {
        return DB::table('client_calls')
            ->selectRaw('operator_id, AVG(call_end_time - call_create_time) AS avg_call_time')
            ->whereBetween('created_at', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->groupBy('operator_id');
    }
}