<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('front.chat.index');
    }

    public function chat(Chat $chat)
    {
        $messages = $chat->messages()->with('user')->limit(50)->orderBy('id', 'DESC')->get();
        $messages = $messages->reverse()->values();

        return view('front.chat.show', compact('chat', 'messages'));
    }
}
