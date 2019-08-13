<?php


namespace App\Models\Traits;


use App\Models\SendSms;

trait HasSms
{
    /**
     * @return morphMany
     */
    public function sms()
    {
        return $this->morphMany(SendSms::class, 'smsable');
    }
}