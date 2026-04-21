<?php

namespace App\Events;

use App\Webhooks\Events\WebhookEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebhookSubscriber implements WebhookEvent
{
    use Dispatchable, SerializesModels;

    /**
     * [$user description]
     * @var [type]
     */

    public $mailinglist;
    public $subscriber;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mailinglist,$subscriber)
    {

        $this->mailinglist = $mailinglist;
        $this->subscriber = $subscriber;
    }

    // public function webhookOwner()
    // {
    //     return $this->owner;
    // }

    public function webhookPayload()
    {
        return [
            'ref'=>$this->mailinglist->slug,
            'subscriber' =>[
             'email' => $this->subscriber->email,
             'firstname' => $this->subscriber->firstname,
             'lastname' => $this->subscriber->lastname,
            ],
        ];
    }
}
