<?php


namespace App\Services\Mango;


use App\Services\Mango\Commands\SendSms;

class MangoService
{
    /**
     * @param SendSms $sendSms
     * @return array|bool
     */
    public function sendSms(SendSms $sendSms)
    {
        if(config('mango.enable_send_sms')){
            return (new MangoClient((array)$sendSms,'commands/sms'))->send();
        }

        return false;
    }
}