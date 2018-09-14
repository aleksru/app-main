<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{   
    protected $guarded = ['id'];
    
    static public function getPriceTypesName():array
    {
        return config('app.price_types');
    }
}
