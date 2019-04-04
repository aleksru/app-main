<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logist extends Model
{
    const STATUS_PREFIX = 'подтвержден';

    protected $guarded = ['id'];
}
