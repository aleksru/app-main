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

    public function index(User $user)
    {
        return $user->roles->pluck('name')->contains('upload_price');
    }

    public function uploadPrice(User $user)
    {
        return $user->roles->pluck('name')->contains('upload_price');
    }


    public function before($user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }
}
