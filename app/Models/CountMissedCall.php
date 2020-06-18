<?php

namespace App\Models;

use App\ClientCall;
use App\Enums\CallTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CountMissedCall extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];

    /**
     * @param string $type
     */
    public static function increase(string $type)
    {
        self::firstOrCreate(['date' => Carbon::today(), 'type' => $type])->increment('count');
    }

    /**
     * @param ClientCall $clientCall
     */
    public static function increaseFromCall(ClientCall $clientCall)
    {
        if($clientCall->isReclamation()){
            self::increase(CallTypes::RECLAMATION);
        }else{
            self::increase(CallTypes::SIMPLE);
        }
    }

    /**
     * @param Carbon $carbon
     * @param string $type
     * @return int
     */
    public static function getCountByType(Carbon $carbon, string $type): int
    {
        return (int)self::firstOrCreate(['date' => $carbon->toDateString(), 'type' => $type])->count;
    }

    /**
     * @param Carbon $carbon
     * @return int
     */
    public static function getCountSimplesByDate(Carbon $carbon): int
    {
        return self::getCountByType($carbon, CallTypes::SIMPLE);
    }

    /**
     * @param Carbon $carbon
     * @return int
     */
    public static function getCountReclamationsByDate(Carbon $carbon): int
    {
        return self::getCountByType($carbon, CallTypes::RECLAMATION);
    }

    /**
     * @return int
     */
    public static function getCountSimplesToday(): int
    {
        return self::getCountSimplesByDate(Carbon::today());
    }

    /**
     * @return int
     */
    public static function getCountReclamationsToday(): int
    {
        return self::getCountReclamationsByDate(Carbon::today());
    }
}
