<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Country;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any countries.
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
     * Determine whether the user can view the country.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Country  $country
     * @return mixed
     */
    public function view(User $user, Country $country)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create countries.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the country.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Country  $country
     * @return mixed
     */
    public function update(User $user, Country $country)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the country.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Country  $country
     * @return mixed
     */
    public function delete(User $user, Country $country)
    {
        //
    }

    /**
     * Determine whether the user can restore the country.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Country  $country
     * @return mixed
     */
    public function restore(User $user, Country $country)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the country.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Country  $country
     * @return mixed
     */
    public function forceDelete(User $user, Country $country)
    {
        //
    }
}
