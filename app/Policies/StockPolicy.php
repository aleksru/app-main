<?php

namespace App\Policies;

use App\Enums\UserGroupsEnums;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->roles->pluck('name')->contains('view_stock') || $user->getUserGroupName() === UserGroupsEnums::STOCK;
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
