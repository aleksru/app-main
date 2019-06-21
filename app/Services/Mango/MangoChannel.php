<?php


namespace App\Services\Mango;


use Illuminate\Notifications\Notification;

class MangoChannel
{
    public function send($notifiable, Notification $notification)
    {
        $notification->toMangoSms($notifiable);
    }
}