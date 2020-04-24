<?php


namespace App\Http\Controllers\Documents;


use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Services\Docs\Courier\RouteList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CourierController extends Controller
{
    public function routeList(Courier $courier, Request $request)
    {
        $routeListData = $courier->getRouteListDataFactory(Carbon::parse($request->get('date')));
        $routeList = App::make(RouteList::class, ['routeListData' => $routeListData]);
        if($request->get('show')){
            //return view('docs.courier.route_list', ['routeListData' => $routeListData]);
            return $routeList->stream();
        }

        return $routeList->download();
    }
}