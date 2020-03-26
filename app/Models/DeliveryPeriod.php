<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DeliveryPeriod extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function failDeliveryDate()
    {
        return $this->morphMany(FailDeliveryDate::class, 'morph');
    }

    public function getPeriodFullAttribute()
    {
        return $this->getPeriodTextAttribute() . ' ' . $this->period;
    }

    public function getPeriodTextAttribute()
    {
        return $this->timeFrom . '-' . $this->timeTo;
    }
}