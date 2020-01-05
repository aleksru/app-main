<?php


namespace App\Events;

use App\Models\Realization;
use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RealizationCopyLogistEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Realization
     */
    public $realization;

    /**
     * RealizationCopyLogistEvent constructor.
     * @param Realization $realization
     */
    public function __construct(Realization $realization)
    {
        $this->realization = $realization;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('order');
    }
}