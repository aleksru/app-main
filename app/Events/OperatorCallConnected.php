<?php

namespace App\Events;

use App\Client;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OperatorCallConnected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Client
     */
    public $client;

    /**
     * @var int
     */
    public $orderId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Client $client, int $orderId)
    {
        $this->user = $user;
        $this->client = $client;
        $this->orderId = $orderId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('operator-incomming.' . $this->user->id);
    }
}
