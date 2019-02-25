<?php

namespace App\Policies;

use App\Enums\RoleOrderEnums;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientCallPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        return $user->roles->pluck('name')->contains(RoleOrderEnums::READ_ORDER)
            || $user->roles->pluck('name')->contains(RoleOrderEnums::CHANGE_ORDER)
            || $user->isOperator();
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
