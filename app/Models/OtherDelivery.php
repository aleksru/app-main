<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class OtherDelivery extends Model
{
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function failDeliveryDate()
    {
        return $this->morphMany(FailDeliveryDate::class, 'morph');
    }
}
