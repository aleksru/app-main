<?php

namespace App\Models;

use App\ClientCall;
use App\Enums\CallTypes;
use App\Services\Calls\Missed\CounterDriverInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CountMissedCall extends Model implements CounterDriverInterface
{
    public $timestamps = false;
    protected $guarded = ['id'];

    /**
     * @param Carbon $carbon
     * @param string $type
     */
    public static function increase(Carbon $carbon, string $type)
    {
        self::firstOrCreate(['date' => $carbon->toDateString(), 'type' => $type])->increment('count');
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
}
