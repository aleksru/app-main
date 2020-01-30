<?php

namespace App\Http\Controllers\Admin;

use App\Chat;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChatRequest;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.chats.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.chats.form');
    }

    /**
     * @param ChatRequest $chatRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ChatRequest $chatRequest)
    {
        $chat = Chat::create($chatRequest->validated());
        $this->setUsers($chat, $chatRequest->get('users'));
        $this->setGroups($chat, $chatRequest->get('groups'));
        return redirect()->route('admin.chats.edit', $chat->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param Chat $chat
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Chat $chat)
    {
        return view('admin.chats.form', compact('chat'));
    }

    /**
     * @param Chat $chat
     * @param ChatRequest $chatRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Chat $chat, ChatRequest $chatRequest)
    {
        $chat->update($chatRequest->validated());
        $this->setUsers($chat, $chatRequest->get('users'));
        $this->setGroups($chat, $chatRequest->get('groups'));
        return redirect()->route('admin.chats.edit', $chat->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param Chat $chat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Chat $chat)
    {
        $chat->delete();
        return response()->json(['message' => 'Успешно удален!']);
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
                        'route' => route('admin.chats.edit', $chat->id),
                    ],
                    'delete' => [
                        'id' => $chat->id,
                        'name' => $chat->name,
                        'route' => route('admin.chats.destroy', $chat->id)
                    ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    private function setGroups(Chat $chat, ?array $groups)
    {
        if($groups === NULL){
            $groups = [];
        }
        $chat->groups()->sync($groups);
    }

    private function setUsers(Chat $chat, ?array $users)
    {
        if($users === NULL){
            $users = [];
        }
        $chat->users()->sync($users);
    }
}
