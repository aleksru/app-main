<?php

namespace App\Jobs;

use App\Client;
use App\Events\SendSmsClient;
use App\Services\Actions\ActionInterface;
use App\Services\Mango\MangoService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SmsAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ActionInterface
     */
    private $action;

    /**
     * SmsAction constructor.
     * @param Client $client
     * @param ActionInterface $action
     */
    public function __construct(Client $client, ActionInterface $action)
    {
        $this->queue = 'sms';
        $this->client = $client;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->action->isCheckAction() && $this->action->isCheckParams()) {
            $message = $this->action->getMessage();
            $res = (new MangoService())->sendSms($message);
            $result = array_key_exists('result', $res) ? $res['result'] : null;
            event(new SendSmsClient($message, $this->client,  $result, $this->action::getNameAction()));
        }
    }
}