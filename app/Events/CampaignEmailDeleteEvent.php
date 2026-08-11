<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignEmailDeleteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $data;
    public $campaignemail;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data,$campaignemail)
    {
        $this->data = $data;
        $this->campaignemail=$campaignemail;
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
