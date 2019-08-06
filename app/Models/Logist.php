<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logist extends Model
{
    const STATUS_PREFIX = 'подтвержден';

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function cities()
    {
        return $this->morphToMany(City::class, 'morph', 'morph_cities');
    }
}
