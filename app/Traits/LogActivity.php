<?php

namespace App\Traits;

/**
 * Trait LogActivity
 *
 * Provides activity logging and audit trail functionality including:
 * - Recording user actions and operations
 * - Tracking who performed actions and on what entities
 * - Storing action properties and custom log names
 * - Creating detailed audit trails for compliance
 *
 * @package App\Traits
 */
trait LogActivity
{
    /**
     * Log an activity to the audit trail.
     *
     * Records an action performed by a user on a model entity with detailed information.
     *
     * @param object $performed_on The model entity that the action was performed on
     * @param object $caused_by The user who performed the action
     * @param array $properties Additional properties or data related to the action
     * @param string $logname The name/category of the log
     * @param string $message The description/message of the action
     *
     * @return void
     */
    public function doActivityLog(object $performed_on, object $caused_by, array $properties, string $logname, string $message): void {
        activity()
            ->performedOn($performed_on)
            ->causedBy($caused_by)
            ->withProperties($properties)
            ->useLog($logname)
            ->log($message);
    }
}
