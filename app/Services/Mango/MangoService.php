<?php


namespace App\Services\Mango;


use App\Services\Mango\Commands\Callback;
use App\Services\Mango\Commands\SendSms;

class MangoService
{
    /**
     * @param SendSms $sendSms
     * @return array|bool
     */
    public function sendSms(SendSms $sendSms)
    {
        if(config('mango.enable_send_sms') && env('APP_ENV') !== 'local'){
            return (new MangoClient((array)$sendSms,'commands/sms'))->send();
        }

        return [];
    }

    /**
     * CallBack client
     *
     * @param callable $callback
     * @return array
     */
    public function callback(Callback $callback)
    {
        return (new MangoClient((array)$callback,'commands/callback'))->send();
    }

    /**
     * Schemas call
     *
     * @return array
     */
    public function getSchemas()
    {
        return (new MangoClient([],'schemas'))->send();
    }

    /**
     * Lines call
     *
     * @return array
     */
    public function getLines()
    {
        return (new MangoClient([],'incominglines'))->send();
    }
}