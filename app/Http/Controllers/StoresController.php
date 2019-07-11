<?php


namespace App\Http\Controllers;


use App\Repositories\StoresRepository;
use App\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class StoresController extends Controller
{
    /**
     * @param StoresRepository $storesRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStateWidget(StoresRepository $storesRepository)
    {
        $stateStores = Cache::remember('state_stores', Carbon::now()->addMinutes(10), function () use ($storesRepository){
            return $storesRepository->getStoresToWidget();
        });
        $html = view('front.widgets.state_stores_list', ['stateStores' => $stateStores])->render();

        return response()->json(['html' => $html]);
    }
}