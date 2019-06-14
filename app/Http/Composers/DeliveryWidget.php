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
        $view->with(['periods' => $periods]);
    }
}