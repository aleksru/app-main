<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JivoChatController extends Controller
{
    public function webhooks(Request $request)
    {
        Log::channel('custom')->error($request->all());
    }
}