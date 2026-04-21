<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AttendanceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $church_id;
    public $entity_id;
    public $entity_name;
    public $title;
    public $category;
    public $date;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($church_id,$entity_id,$entity_name,$title,$category,$date)
    {
        //
        $this->church_id    = $church_id;
        $this->entity_id    = $entity_id;
        $this->entity_name  = $entity_name;
        $this->title        = $title;
        $this->category     = $category;
        $this->date         = $date;
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
