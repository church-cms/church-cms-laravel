<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PrayerReminderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $church_id;
    public $from;
    public $subject;
    public $message;
    public $entity_id;
    public $entity_name;
    public $via;
    public $executed_at;
    public $data;
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($church_id,$from,$subject,$message,$entity_id,$entity_name,$via,$data,$executed_at,$user_id)
    {
        //
        $this->church_id   = $church_id;
        $this->from        = $from;
        $this->subject     = $subject;
        $this->message     = $message;
        $this->entity_id   = $entity_id;
        $this->entity_name = $entity_name;
        $this->via         = $via;
        $this->executed_at = $executed_at;
        $this->data        = $data;
        $this->user_id     = $user_id;
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
