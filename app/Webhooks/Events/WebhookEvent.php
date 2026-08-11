<?php

namespace App\Webhooks\Events;

interface WebhookEvent
{
    public function webhookPayload();
   // public function webhookOwner();
}