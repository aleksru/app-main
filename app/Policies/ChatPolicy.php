<?php

namespace App\Policies;

use App\User;
use App\Chat;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the chat.
     *
     * @param  \App\User  $user
     * @param  \App\Chat  $chat
     * @return mixed
     */
    public function view(User $user, Chat $chat)
    {
        return $chat->users->contains($user->id) || ($user->group && $chat->groups->contains($user->group->id));
    }

    /**
     * Determine whether the user can create chats.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the chat.
     *
     * @param  \App\User  $user
     * @param  \App\Chat  $chat
     * @return mixed
     */
    public function update(User $user, Chat $chat)
    {
        //
    }

    /**
     * Determine whether the user can delete the chat.
     *
     * @param  \App\User  $user
     * @param  \App\Chat  $chat
     * @return mixed
     */
    public function delete(User $user, Chat $chat)
    {
        //
    }

    /**
     * Determine whether the user can restore the chat.
     *
     * @param  \App\User  $user
     * @param  \App\Chat  $chat
     * @return mixed
     */
    public function restore(User $user, Chat $chat)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the chat.
     *
     * @param  \App\User  $user
     * @param  \App\Chat  $chat
     * @return mixed
     */
    public function forceDelete(User $user, Chat $chat)
    {
        //
    }

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }
}
