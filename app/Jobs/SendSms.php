<?php

namespace App\Jobs;

use App\Client;
use App\Events\SendSmsClient;
use App\Services\Mango\MangoService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Mango\Commands\SendSms as MangoSendSMS;
use Illuminate\Support\Facades\Log;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var MangoSendSMS
     */
    private $sendSms;

    /**
     * SendSms constructor.
     * @param Client $client
     * @param MangoSendSMS $sendSms
     */
    public function __construct(Client $client, MangoSendSMS $sendSms)
    {
        $this->queue = 'sms';
        $this->client = $client;
        $this->sendSms = $sendSms;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $res = app(MangoService::class)->sendSms($this->sendSms);
        event(new SendSmsClient($this->sendSms, $this->client, (int)$res['result'] ?? null));
    }
}
