<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SendMessageAllEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $church_id;
    public $admin_email;
    public $admin;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data,$church_id,$admin_email,$admin)
    {
        //
        $this->data = $data;
        $this->church_id = $church_id;
        $this->admin_email = $admin_email;
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
