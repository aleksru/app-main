<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Order;

class ApiMangoController extends Controller
{
    public function index(Request $request)
    {
        $data = json_decode($request->json, true);

        //Проверка на первй входящий звонок
        if($data['seq'] == 1 && $data['location'] == 'ivr' && $data['call_state'] == 'Appeared'){
            //Order::where('phone', $data['from']['number'])->get();
            Log::error($request->all());
            Log::error(Order::where('phone', $data['from']['number'])->get()->toArray());
        }

        return ['status' => 200];
    }
}
