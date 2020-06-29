<?php


namespace App\Services\Calls\Missed;


use App\ClientCall;
use App\Enums\CallTypes;
use App\Models\CountMissedCall;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

final class CounterMissed
{
    /**
     * @var CounterDriverInterface
     */
    private static $driver;

    /**
     * @return CounterDriverInterface
     */
    private static function getDriverInstance()
    {
        if( ! self::$driver ){
            self::$driver = CountMissedCall::class;
        }

        return self::$driver;
    }

    /**
     * @param string $type
     */
    public static function increase(string $type)
    {
        $hour = Carbon::now()->hour;
        if($hour >= 9 && $hour < 23){
            self::getDriverInstance()::increase(Carbon::today(), $type);
        }
    }

    /**
     * @param Carbon $carbon
     * @param string $type
     * @return int
     */
    public static function getCountByType(Carbon $carbon, string $type): int
    {
        return self::getDriverInstance()::getCountByType($carbon, $type);
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
            Log::channel('custom')->error($clientCall);
        }
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