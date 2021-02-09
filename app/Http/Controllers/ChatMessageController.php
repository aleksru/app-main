<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\NewChatMessage;
use App\Http\Requests\ChatMessageRequest;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    /**
     * @param Chat $chat
     * @param ChatMessageRequest $chatMessageRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Chat $chat, ChatMessageRequest $chatMessageRequest)
    {
        $message = $chat->messages()->create($chatMessageRequest->validated());
        event(new NewChatMessage($message));
        $message->notifyNewMessage();
        return response()->json(['message' => 'Успешно отправлено!']);
    }
}
