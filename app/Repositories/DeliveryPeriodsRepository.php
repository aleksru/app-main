<?php


namespace App\Repositories;


use App\Models\DeliveryPeriod;
use App\Models\OtherDelivery;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DeliveryPeriodsRepository
{
    /**
     * @param Carbon $date
     * @return Collection
     */
    public function getDeliveryPeriods(Carbon $date) : Collection
    {
        $selectedDate = $date;
        $periods = DeliveryPeriod::with(['failDeliveryDate' => function($query) use ($selectedDate){
            $query->filterFailsByDate($selectedDate);
        }])->get();
        OtherDelivery::with(['failDeliveryDate' => function($query) use ($selectedDate){
            $query->filterFailsByDate($selectedDate);
        }])->get()->each(function ($item) use (&$periods){
            $periods->push($item);
        });

        return $periods;
    }
}