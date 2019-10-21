<?php

namespace App\Listeners;

use App\Events\SendSmsClient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveSmsLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendSmsClient  $event
     * @return void
     */
    public function handle(SendSmsClient $event)
    {
        $event->model->sms()->create([
            'text'           => $event->sendSms->text,
            'command_id'     => $event->sendSms->command_id,
            'sms_render'     => $event->sendSms->sms_sender,
            'from_extension' => $event->sendSms->from_extension,
            'to_number'      => $event->sendSms->to_number,
            'result'         => $event->resultCode,
            'action'         => $event->action
        ]);
    }
}
