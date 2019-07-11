<?php


namespace App\Http\Composers;

use App\Repositories\StoresRepository;
use App\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class StateStoreWidget
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $stateStores = Cache::remember('state_stores', Carbon::now()->addMinutes(10), function (){
            return app(StoresRepository::class)->getStoresToWidget();
        });

        $view->with(['stateStores' => $stateStores]);
    }
}