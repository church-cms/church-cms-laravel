<?php

namespace App\Listeners;

use App\Events\PushEvent;
use App\Traits\SendPushNotification;
use App\Models\User;
use App\Notifications\SendDeviceNotification;
use Illuminate\Support\Facades\Log;

class PushEventListener //implements ShouldQueue
{

    use SendPushNotification;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PushEvent $event
     * @return void
     */
    public function handle(PushEvent $event)
    {
        //dump($event);


        // Skip push notifications when Firebase is not configured
        $credentials = config('firebase.projects.app.credentials');
        if (empty($credentials)) {
            Log::warning('PushEventListener: Firebase credentials not configured, skipping push notifications.');
            return;
        }

        $users = User::where('church_id', $event->data['church_id'])->whereNotNull('platform_token')->get();

        foreach ($users as $user) {
            // A failed push must never break the calling request
            try {
                $user->notify(new SendDeviceNotification($event->data, $user->platform_token));
            } catch (\Throwable $e) {
                Log::error('PushEventListener: ' . $e->getMessage(), ['user_id' => $user->id]);
            }
        }
        // Mail::to($event->queue->to)->queue(new ReminderMail($event->queue));
    }
}
