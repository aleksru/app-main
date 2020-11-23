<?php


namespace App\Repositories;

use App\Models\OtherStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class RealizationStatusesRepository
{
    public function getSuccessStatus()
    {
        return Cache::remember('ID_REALIZATION_STATUS_SUCCESS', Carbon::now()->addHours(4) ,function(){
            return OtherStatus::typeRealizationStatusSuccess()->first();
        });
    }

    public function getRefusalStatus()
    {
        return Cache::remember('ID_REALIZATION_STATUS_REFUSAL', Carbon::now()->addHours(4) ,function(){
            return OtherStatus::typeRealizationStatusRefusal()->first();
        });
    }
}
