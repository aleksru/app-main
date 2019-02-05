<?php

namespace App\Policies;

use App\Enums\UserGroupsEnums;
use App\User;
use App\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->roles->pluck('name')->contains('read_orders') || $user->roles->pluck('name')->contains('change_orders')
                    || $user->getUserGroupName() === UserGroupsEnums::OPERATOR;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function show(User $user)
    {
        return $user->roles->pluck('name')->contains('read_orders') || $user->roles->pluck('name')->contains('change_orders')
            || $user->getUserGroupName() === UserGroupsEnums::OPERATOR || $user->getUserGroupName() === UserGroupsEnums::STOCK;
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles->pluck('name')->contains('change_orders') || $user->getUserGroupName() === UserGroupsEnums::OPERATOR;
    }

    /**
     * Determine whether the user can store orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function store(User $user)
    {
        return $user->roles->pluck('name')->contains('change_orders') || $user->getUserGroupName() === UserGroupsEnums::OPERATOR;
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
