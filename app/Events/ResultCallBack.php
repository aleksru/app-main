<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class ResultCallBack implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    private $commandId;

    /**
     * @var string
     */
    public $result;

    /**
     * ResultCallBack constructor.
     * @param string $commandId
     * @param string $result
     */
    public function __construct(string $commandId, string $result)
    {
        $this->commandId = $commandId;
        $this->result = $result;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::channel('custom')->error(['ResultCallBack', $this->commandId, $this->result]);
        return new PrivateChannel('operator-callback.' . $this->commandId);
    }
}
