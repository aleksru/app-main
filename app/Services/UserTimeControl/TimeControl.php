<?php


namespace App\Services\UserTimeControl;

use App\User;
use Carbon\Carbon;

class TimeControl
{
    /**
     * @param User $user
     */
    public function logon(User $user)
    {
        $user->controlTimes()->create([
            'logon' => Carbon::now()
        ]);
    }

    /**
     * @param User $user
     */
    public function logout(User $user)
    {
        $contTime = $user->getUnClosedTime();
        if($contTime){
            $contTime->logout = Carbon::now();
            $contTime->save();
        }
    }
}