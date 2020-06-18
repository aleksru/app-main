<?php

namespace App\Observers;

use App\Enums\MissedCallTypeEnums;
use App\Events\MissedCallEvent;
use App\MissedCall;
use App\Models\CountMissedCall;

class MissedCallObserver
{
    /**
     * Handle the missed call "created" event.
     *
     * @param  \App\MissedCall  $missedCall
     * @return void
     */
    public function created(MissedCall $missedCall)
    {
        CountMissedCall::increaseFromCall($missedCall->clientCall);
        event(new MissedCallEvent($missedCall->clientCall, MissedCallTypeEnums::ADD));
    }

    /**
     * Handle the missed call "deleted" event.
     *
     * @param  \App\MissedCall  $missedCall
     * @return void
     */
    public function deleted(MissedCall $missedCall)
    {
        event(new MissedCallEvent($missedCall->clientCall, MissedCallTypeEnums::DELETE));
    }
}
