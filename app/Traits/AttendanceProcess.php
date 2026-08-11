<?php

/**
 * Trait for processing AttendanceProcess
 */
namespace App\Traits;

use App\Models\Attendance;
use Exception;
use log;

/**
 * Trait for processing attendance records.
 *
 * Provides functionality for:
 * - Creating attendance records for users
 * - Tracking attendance for events and entities
 * - Associating attendance with users and churches
 *
 * @package App\Traits
 */
trait AttendanceProcess
{
    /**
     * Create an attendance record for a user.
     *
     * Creates and saves a new attendance record with the provided parameters.
     * Sets initial presence status to 0 (absent).
     *
     * @param int $church_id The church ID associated with the attendance
     * @param int $user_id The user ID for whom attendance is being recorded
     * @param int $entity_id The entity ID (event, group, etc.) for the attendance
     * @param string $entity_name The name/type of the entity
     * @param string $title The title or description of the attendance record
     * @param string $category The category of attendance (e.g., 'event', 'service')
     * @param string $date The date of the attendance
     *
     * @return void
     */
    public function createAttendance(
        int $church_id,
        int $user_id,
        int $entity_id,
        string $entity_name,
        string $title,
        string $category,
        string $date
    ): void {
        try {
            $attendance              = new Attendance;

            $attendance->church_id   = $church_id;
            $attendance->user_id     = $user_id;
            $attendance->entity_id   = $entity_id;
            $attendance->entity_name = $entity_name;
            $attendance->title       = $title;
            $attendance->category    = $category;
            $attendance->date        = $date;
            $attendance->is_present  = 0;

            $attendance->save();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
