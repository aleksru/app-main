<?php


namespace App\Events;

use App\ClientCall;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MissedCallEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var ClientCall
     */
    public $clientCall;

    /**
     * @var string
     */
    public $type;

    /**
     * MissedCallEvent constructor.
     * @param ClientCall $clientCall
     * @param string $type
     */
    public function __construct(ClientCall $clientCall, string $type)
    {
        $this->clientCall = $clientCall;
        $this->type = $type;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return new Channel('missed-calls');
    }
}