<?php


namespace App\Notifications;

use App\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification implements ShouldQueue
{
    use Queueable;
    protected $chatMessageId;
    protected $chatMessage;
    protected $chat;

    /**
     * NewChatMessage constructor.
     * @param int $chatMessageId
     */
    public function __construct(int $chatMessageId)
    {
        $this->chatMessageId = $chatMessageId;
        $this->chatMessage = ChatMessage::findOrFail($this->chatMessageId);
        $this->chat = $this->chatMessage->chat;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'content' => (view('front.notifications.user.chat_message', [
                'chatId' => $this->chat->id,
                'chatName' => $this->chat->name
            ])->render())
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Новое сообщение в чате ' . $this->chat->name
        ]);
    }
}
