<?php

namespace App\Events;

use App\Services\Mango\Commands\SendSms;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendSmsClient
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var SendSms
     */
    public $sendSms;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var int|null
     */
    public $resultCode;

    /**
     * @var null|string
     */
    public $action;

    /**
     * SendSmsClient constructor.
     * @param SendSms $sendSms
     * @param Model $model
     * @param int|null $resultCode
     * @param string|null $action
     */
    public function __construct(SendSms $sendSms, Model $model, int $resultCode = null, string $action = null)
    {
        $this->sendSms = $sendSms;
        $this->model = $model;
        $this->resultCode = $resultCode;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
