<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\Invite;


class TestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invite;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invite $invite)
    {
        //
        $this->invite = $invite;
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
