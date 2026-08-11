<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AfterExpiredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


     public $queue;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($queue)
    {
        //
        $this->queue = $queue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        /*return new PrivateChannel('channel-name');*/
        return [];
    }

    public function broadcastWith()
    {
        return [];
    }

}
