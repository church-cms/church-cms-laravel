<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any activity logs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the activity log.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ActivityLog  $activityLog
     * @return mixed
     */
    public function view(User $user, ActivityLog $activityLog)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create activity logs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the activity log.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ActivityLog  $activityLog
     * @return mixed
     */
    public function update(User $user, ActivityLog $activityLog)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the activity log.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ActivityLog  $activityLog
     * @return mixed
     */
    public function delete(User $user, ActivityLog $activityLog)
    {
        //
    }

    /**
     * Determine whether the user can restore the activity log.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ActivityLog  $activityLog
     * @return mixed
     */
    public function restore(User $user, ActivityLog $activityLog)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the activity log.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ActivityLog  $activityLog
     * @return mixed
     */
    public function forceDelete(User $user, ActivityLog $activityLog)
    {
        //
    }
}
