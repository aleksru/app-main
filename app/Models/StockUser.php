<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockUser extends Model
{
    const STATUS_PREFIX = 'склад';

    protected $guarded = ['id'];

}
