<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;

class CampaignEmailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $campaignemail;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($campaignemail,$data)
    {
        $this->campaignemail    = $campaignemail;
        $this->data             = $data;   
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