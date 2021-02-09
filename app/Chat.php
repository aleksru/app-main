<?php

namespace App;

use App\Models\UserGroup;
use App\Notifications\NewChatMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Chat
 * @package App
 * @property $messages Collection|ChatMessage[]
 * @property $users Collection|User[]
 * @property $groups Collection|UserGroup[]
 */
class Chat extends Model
{
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'morph', 'morph_chats');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function groups()
    {
        return $this->morphedByMany(UserGroup::class, 'morph', 'morph_chats');
    }

    public function getMembers(): Collection
    {
        return $this->groups->map(function ($group){
            return $group->users;
        })->reduce(function ($prev, $curr){
            return $prev->merge($curr);
        }, collect())
        ->merge($this->users)
        ->unique('id');
    }

    public function notifyOnNewMessage(ChatMessage $message)
    {
        $this->getMembers()->filter(function ($member) use ($message) {
            return $member->id !== $message->user_id;
        })->each(function ($member) use ($message) {
            $member->notify(new NewChatMessage($message->id));
        });
    }
}
