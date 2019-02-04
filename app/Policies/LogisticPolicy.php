<?php

namespace App\Policies;

use App\Enums\UserGroupsEnums;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogisticPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->roles->pluck('name')->contains('view_logistics') || $user->isLogist();
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
