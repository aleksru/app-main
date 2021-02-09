<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ChatMessage
 * @package App
 */
class ChatMessage extends Model
{
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifyNewMessage()
    {
        $this->chat->notifyOnNewMessage($this);
    }
}
