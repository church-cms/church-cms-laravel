<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class SendDeviceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $data;
    protected string $token;

    public function __construct(array $data, string $token)
    {
        $this->queue = 'notification';

        $this->data = $data;
        $this->token = $token;
    }

    /**
     * Notification channels.
     */
    public function via($notifiable): array
    {
        return [FcmChannel::class];
    }

    /**
     * Send FCM notification.
     */
    public function toFcm($notifiable): FcmMessage
    {
        $title   = (string) ($this->data['type'] ?? 'Notification');
        $message = (string) ($this->data['message'] ?? '');
        $id      = (string) ($this->data['id'] ?? '');

        Log::info('Sending FCM Notification', [
            'token' => $this->token,
            'payload' => [
                'id' => $id,
                'type' => $title,
                'message' => $message,
            ]
        ]);

        return FcmMessage::create()
            ->token($this->token)

            // Custom Data Payload
            ->data([
                'id' => $id,
                'type' => $title,
                'message' => $message,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ])

            // Notification Payload
            ->notification(
                FcmNotification::create(
                    $title,
                    $message
                )
            )

            ->custom([
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'default',
                        'color' => '#0A0A0A',
                    ],
                ],

                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Firebase project.
     */
    public function fcmProject($notifiable, $message): ?string
    {
        return 'app';
    }

    /**
     * Failed job handler.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('FCM Notification Failed', [
            'token' => $this->token,
            'data' => $this->data,
            'error' => $exception->getMessage(),
        ]);
    }
}