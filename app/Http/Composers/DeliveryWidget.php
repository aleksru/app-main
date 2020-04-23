<?php


namespace App\Http\Composers;

use App\Repositories\DeliveryPeriodsRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DeliveryWidget
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $periods = Cache::remember('delivery_periods', Carbon::now()->addMinutes(2), function (){
            $deliveryPeriodsRepository = app(DeliveryPeriodsRepository::class);
            return $deliveryPeriodsRepository->getDeliveryPeriods(Carbon::today());
        });

        $periodsTomorrow = Cache::remember('delivery_periods_next_day', Carbon::now()->addMinutes(2), function (){
            $deliveryPeriodsRepository = app(DeliveryPeriodsRepository::class);
            return $deliveryPeriodsRepository->getDeliveryPeriods(Carbon::tomorrow());
        });

        $periodsTomorrow2 = Cache::remember('delivery_periods_next_day_2', Carbon::now()->addMinutes(2), function (){
            $deliveryPeriodsRepository = app(DeliveryPeriodsRepository::class);
            return $deliveryPeriodsRepository->getDeliveryPeriods(Carbon::today()->addDay(2));
        });

        $view->with(['periods' => $periods, 'periodsTomorrow' => $periodsTomorrow, 'periodsTomorrow2' => $periodsTomorrow2]);
    }
}