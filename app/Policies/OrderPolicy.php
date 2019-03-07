<?php

namespace App\Policies;

use App\Enums\RoleOrderEnums;
use App\Enums\UserGroupsEnums;
use App\User;
use App\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * @var RoleOrderEnums
     */
    protected $roleOrderEnums;

    /**
     * OrderPolicy constructor.
     * @param RoleOrderEnums $roleOrderEnums
     */
    public function __construct (RoleOrderEnums $roleOrderEnums)
    {
        $this->roleOrderEnums = $roleOrderEnums;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->roles->pluck('name')->contains($this->roleOrderEnums::READ_ORDER)
            || $user->roles->pluck('name')->contains($this->roleOrderEnums::CHANGE_ORDER)
            || $user->isOperator();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function show(User $user)
    {
        return $user->roles->pluck('name')->contains($this->roleOrderEnums::READ_ORDER)
            || $user->roles->pluck('name')->contains($this->roleOrderEnums::CHANGE_ORDER)
            || $user->isOperator() || $user->isStock() || $user->isLogist();
    }

    /**
     * Determine whether the user can create orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles->pluck('name')->contains($this->roleOrderEnums::CHANGE_ORDER)
            || $user->isOperator();
    }

    /**
     * Determine whether the user can store orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function store(User $user)
    {
        return $user->roles->pluck('name')->contains($this->roleOrderEnums::CHANGE_ORDER)
            || $user->isOperator();
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
        return $user->roles->pluck('name')->contains($this->roleOrderEnums::CHANGE_ORDER)
            || $user->isOperator()
            || $user->isLogist()
            || $user->isStock();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function commentLogist(User $user)
    {
        return $user->isLogist();
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
