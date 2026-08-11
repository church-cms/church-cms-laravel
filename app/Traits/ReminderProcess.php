<?php
namespace App\Traits;

use App\Models\Reminder;
use Exception;
use Log;

/**
 * Trait ReminderProcess
 *
 * Manages reminder creation and scheduling including:
 * - Creating reminders for various events and entities
 * - Supporting multiple notification channels (email, SMS, notification)
 * - Scheduling reminders with execution times
 * - Tracking reminder status and data
 *
 * @package App\Traits
 */
trait ReminderProcess
{

    /**
     * Create a reminder for an entity.
     *
     * Creates a reminder record for a specific entity with notification preferences.
     * Supports multiple notification channels (email, SMS, in-app notification).
     *
     * @param int $church_id The church ID associated with the reminder
     * @param string $from The sender/from address or identifier
     * @param string $to The recipient address/identifier
     * @param string $subject The reminder subject/title
     * @param string $message The reminder message text
     * @param int $entity_id The ID of the entity the reminder is for
     * @param string $entity_name The type/class of the entity (e.g., 'App\\Models\\Event')
     * @param string $via The notification channel (email, sms, notification)
     * @param mixed $data Additional data associated with the reminder
     * @param string $executed_at The scheduled execution date/time
     *
     * @return void
     */
    public function createReminder(
        int $church_id,
        string $from,
        string $to,
        string $subject,
        string $message,
        int $entity_id,
        string $entity_name,
        string $via,
        $data,
        string $executed_at
    ): void {
        try {
            $reminder              = new Reminder;
            $reminder->church_id   = $church_id;
            $reminder->from        = $from;
            $reminder->to          = $to;
            $reminder->subject     = $subject;
            $reminder->message     = $message;
            $reminder->entity_id   = $entity_id;
            $reminder->entity_name = $entity_name;
            $reminder->via         = $via;
            $reminder->executed_at = $executed_at;
            $reminder->data        = $data;
            $reminder->save();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
