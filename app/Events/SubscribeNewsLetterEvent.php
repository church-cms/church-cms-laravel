<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscribeNewsLetterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $church_id;
    public $admin;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request,$church_id,$admin)
    {
        //
        $this->request = $request;
        $this->church_id = $church_id;
        $this->admin = $admin;
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