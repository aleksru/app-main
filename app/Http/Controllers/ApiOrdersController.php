<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class ApiOrdersController extends Controller
{
    public function api(Request $req)
    {
        //debug($req->all());
        return json_encode('ok');
    }
}