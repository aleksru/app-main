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
        $this->authorize('view', $chat);
        $messages = $chat->messages()->with('user')->limit(50)->orderBy('id', 'DESC')->get();
        $messages = $messages->reverse()->values();

        return view('front.chat.show', compact('chat', 'messages'));
    }

    /**
     * Datatable
     * @return string
     */
    public function datatable()
    {
        return datatables() ->of(Chat::query())
            ->editColumn('actions', function (Chat $chat) {
                return view('datatable.actions', [
                    'edit' => [
                        'route' => route('chat.show', $chat->id),
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
