<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportSubscriberEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subscriber_id;
    public $update;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($subscriber_id,$update)
    {
      
       $this->subscriber_id=$subscriber_id;
       $this->update=$update;

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
