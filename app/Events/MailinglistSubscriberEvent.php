<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MailinglistSubscriberEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $data;
    public $MailinglistSubscriber;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($MailinglistSubscriber,$data)
    {
        //dump($data);
       
        $this->MailinglistSubscriber=$MailinglistSubscriber;
        $this->data=$data;
       
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
