<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriberConfirmEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $mailinglist_id;
    public $subscriber_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mailinglist_id,$subscriber_id)
    {
        $this->mailinglist_id=$mailinglist_id;
        $this->subscriber_id=$subscriber_id;
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
