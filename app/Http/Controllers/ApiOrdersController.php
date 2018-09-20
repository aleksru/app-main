<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ApiSetOrderRequest;
use App\Order;

class ApiOrdersController extends Controller
{
    public function api(ApiSetOrderRequest $req)
    {
        $data = $req->validated();
        $data['products'] = json_decode($req->products, true);

        Order::create($data);
        
        return json_encode('ok');
    }
}