<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FailDeliveryDate extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'stop' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveryPeriod()
    {
        return $this->morphTo(DeliveryPeriod::class);
    }

    /**
     * @param Builder $query
     * @param Carbon $date
     * @return $this
     */
    public function scopeFilterFailsByDate(Builder $query, Carbon $date)
    {
        return $query->whereDate('date', '=', $date)
                ->where('stop', '=', 1);
    }

}
