<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

        'App\Events\TestEvent' => [
            'App\Listeners\TestEventListener',
        ],

        'App\Events\CalendarEvent' => [
            'App\Listeners\CalendarEventListener',
        ],

        'App\Events\AfterExpiredEvent' => [
            'App\Listeners\AfterExpiredListener',
        ],

        'App\Events\AfterSubscriptionExpiredEvent' => [
            'App\Listeners\AfterSubscriptionExpiredListener',
        ],

        'App\Events\SermonEvent' => [
            'App\Listeners\SermonEventListener',
        ],

        'App\Events\SermonLinkEvent' => [
            'App\Listeners\SermonLinkListener',
        ],

        'App\Events\ReminderEvent' => [
            'App\Listeners\ReminderEventListener',
        ],

        'App\Events\SendMessageEvent' => [
            'App\Listeners\SendMessageEventListener',
        ],

        'App\Events\SendMessageAllEvent' => [
            'App\Listeners\SendMessageAllEventListener',
        ],

        'App\Events\ReminderMailEvent' => [
            'App\Listeners\ReminderMailListener',
        ],

        'App\Events\UserReminderEvent' => [
            'App\Listeners\UserReminderEventListener',
        ],

        'App\Events\BirthdayReminderMailEvent' => [
            'App\Listeners\BirthdayReminderMailEventListener',
        ],

        'App\Events\AdminBirthdayReminderEvent' => [
            'App\Listeners\AdminBirthdayReminderEventListener',
        ],

        'App\Events\PushEvent' => [
            'App\Listeners\PushEventListener',
        ],

        'App\Events\PrayerRequestReminderMailEvent' => [
            'App\Listeners\PrayerRequestReminderMailEventListener',
        ],

        'App\Events\BirthdayPushEvent' => [
            'App\Listeners\BirthdayPushEventListener',
        ],

        'App\Events\VerificationMailEvent' => [
            'App\Listeners\VerificationMailEventListener',
        ],

        'App\Events\PrayerReminderEvent' => [
            'App\Listeners\PrayerReminderEventListener',
        ],

        'App\Events\AttendanceEvent' => [
            'App\Listeners\AttendanceEventListener',
        ],

        'App\Events\UserNotifyGroupEvent' => [
            'App\Listeners\UserNotifyGroupEventListener',
        ],

        'App\Events\SinglePushEvent' => [
            'App\Listeners\SinglePushEventListener',
        ],

        'App\Events\PrayerRequestEvent' => [
            'App\Listeners\PrayerRequestEventListener',
        ],

        'App\Events\CampaignEmailEvent' => [
            'App\Listeners\CampaignEmailListener',
        ],

        'App\Events\CampaignEmailDeleteEvent' => [
            'App\Listeners\CampaignEmailDeleteListener',
        ],

        'App\Events\MailinglistSubscriberEvent' => [
            'App\Listeners\MailinglistSubscriberListener',
        ],

        'App\Events\CampaignDeleteEvent' => [
            'App\Listeners\CampaignDeleteListener',
        ],

        //  'App\Events\SubscriberDeleteEvent' => [
        //     'App\Listeners\SubscriberDeleteListener',
        // ],

        'jdavidbakr\MailTracker\Events\EmailSentEvent' => [
            'App\Listeners\EmailSent',
        ],

        'jdavidbakr\MailTracker\Events\ViewEmailEvent' => [
            'App\Listeners\EmailViewed',
        ],

        'jdavidbakr\MailTracker\Events\LinkClickedEvent' => [
            'App\Listeners\EmailLinkClicked',
        ],

        // 'jdavidbakr\MailTracker\Events\EmailDeliveredEvent' => [
        //     'App\Listeners\EmailDelivered',
        // ],

        // 'jdavidbakr\MailTracker\Events\ComplaintMessageEvent' => [
        //     'App\Listeners\EmailComplaint',
        // ],

        // 'jdavidbakr\MailTracker\Events\PermanentBouncedMessageEvent' => [
        //     'App\Listeners\BouncedEmail',
        // ],

        'App\Events\SubscriberConfirmEvent' => [
            'App\Listeners\SubscriberConfirmListener',
        ],

        'App\Events\ImportSubscriberEvent' => [
            'App\Listeners\ImportSubscriberListener',
        ],

        'App\Events\SubscribeNewsLetterEvent' => [
            'App\Listeners\SubscribeNewsLetterEventListener',
        ],

        'App\Events\Notification\SingleNotificationEvent' => [
            'App\Listeners\Notification\SingleNotificationEventListener',
        ],

        'App\Events\Notification\PrayerNotificationEvent' => [
            'App\Listeners\Notification\PrayerNotificationEventListener',
        ],

        'App\Events\Notification\PushNotificationEvent' => [
            'App\Listeners\Notification\PushNotificationEventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
