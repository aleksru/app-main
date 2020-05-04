<?php


namespace App\Http\Controllers\Documents;


use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Services\Docs\Courier\CheckList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CheckListController extends Controller
{
    public function checkList(Courier $courier, Request $request)
    {
        $checkListData = $courier->getCheckListDataFactory(Carbon::parse($request->get('date')));
        $checkList = App::make(CheckList::class, ['checkListData' => $checkListData]);
        if($request->get('show')){
            //return view('docs.courier.check_list', ['checkListData' => $courier->getCheckListDataFactory(Carbon::parse($request->get('date')))]);
            return $checkList->stream();
        }

        return $checkList->download();
    }
}