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
}