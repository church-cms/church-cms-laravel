<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Usergroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsergroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any usergroups.
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
     * Determine whether the user can view the usergroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Usergroup  $usergroup
     * @return mixed
     */
    public function view(User $user, Usergroup $usergroup)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create usergroups.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the usergroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Usergroup  $usergroup
     * @return mixed
     */
    public function update(User $user, Usergroup $usergroup)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the usergroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Usergroup  $usergroup
     * @return mixed
     */
    public function delete(User $user, Usergroup $usergroup)
    {
        //
    }

    /**
     * Determine whether the user can restore the usergroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Usergroup  $usergroup
     * @return mixed
     */
    public function restore(User $user, Usergroup $usergroup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the usergroup.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Usergroup  $usergroup
     * @return mixed
     */
    public function forceDelete(User $user, Usergroup $usergroup)
    {
        //
    }
}
