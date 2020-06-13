<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MissedCall extends Model
{
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clientCall()
    {
        return $this->hasOne(ClientCall::class);
    }

    public static function excludeOnNumber(string $number)
    {
        $ids = MissedCall::query()
            ->select('missed_calls.id')
            ->join('client_calls', 'missed_calls.client_call_id', '=', 'client_calls.id')
            ->where('client_calls.from_number', $number)
            ->get();
        if( ! $ids->isEmpty() ){
            MissedCall::query()->whereIn('id', $ids)->delete();
        }
    }

    public static function excludeOnClientCall(ClientCall $clientCall)
    {
        self::excludeOnNumber($clientCall->from_number);
    }

    /**
     * @param ClientCall $clientCall
     * @return MissedCall
     */
    public static function createOnClientCall(ClientCall $clientCall):self
    {
        if( ! $clientCall->isIncoming() ){
            throw new \InvalidArgumentException('ClientCall missed is only incoming!');
        }

        return self::create([
            'client_call_id' => $clientCall->id
        ]);
    }
}
