<?php

namespace App\Policies;

use App\Client;
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
        return $user->roles->pluck('name')->contains('read_orders') || $user->roles->pluck('name')->contains('change_orders')
            || $user->getUserGroupName() === UserGroupsEnums::OPERATOR;
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->roles->pluck('name')->contains('change_orders') || $user->getUserGroupName() === UserGroupsEnums::OPERATOR;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function createOrderClient(User $user)
    {
        return $user->roles->pluck('name')->contains('change_orders') || $user->getUserGroupName() === UserGroupsEnums::OPERATOR;
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
