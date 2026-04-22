<?php

namespace App\Policies;

use App\Models\User;
use App\Models\State;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any states.
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
     * Determine whether the user can view the state.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\State  $state
     * @return mixed
     */
    public function view(User $user, State $state)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create states.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the state.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\State  $state
     * @return mixed
     */
    public function update(User $user, State $state)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the state.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\State  $state
     * @return mixed
     */
    public function delete(User $user, State $state)
    {
        //
    }

    /**
     * Determine whether the user can restore the state.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\State  $state
     * @return mixed
     */
    public function restore(User $user, State $state)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the state.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\State  $state
     * @return mixed
     */
    public function forceDelete(User $user, State $state)
    {
        //
    }
}
