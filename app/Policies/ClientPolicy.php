<?php

namespace App\Policies;

use App\Client;
use App\Enums\RoleOrderEnums;
use App\Enums\UserGroupsEnums;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->roles->pluck('name')->contains(RoleOrderEnums::READ_ORDER)
            || $user->roles->pluck('name')->contains(RoleOrderEnums::CHANGE_ORDER)
            || $user->isOperator();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->roles->pluck('name')->contains('change_orders') || $user->isOperator();
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
