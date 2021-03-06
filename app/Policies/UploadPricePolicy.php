<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UploadPricePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->roles->pluck('name')->contains('change_price_list');
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function uploadPrice(User $user)
    {
        return $user->roles->pluck('name')->contains('change_price_list');
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
