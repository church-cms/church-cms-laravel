<?php

namespace App\Traits;

use App\Events\Notification\SingleNotificationEvent;
use App\Models\User;

/**
 * Trait SendWebNotification
 *
 * Handles in-application web notifications including:
 * - Triggering notification events for users
 * - Formatting and sending real-time notifications
 * - Broadcasting notifications through websockets
 *
 * @package App\Traits
 */
trait SendWebNotification

{
    /**
     * Add a notification for a user.
     *
     * Triggers a notification event that broadcasts to the user in real-time
     * through websocket connections.
     *
     * @param array $data The notification data containing message and other details
     * @param \App\Models\User $user The user to send the notification to
     *
     * @return void
     */
    public function addNotification(array $data, User $user): void
    {
        $array = [];

        $array['user']     = $user;
        $array['details']  = $data['message'];

        event(new SingleNotificationEvent($array));
    }
}
