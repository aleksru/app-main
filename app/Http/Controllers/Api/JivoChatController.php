<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JivoChatController
{
    public function webhooks(Request $request)
    {
        Log::channel('custom')->error($request->all());
    }
}