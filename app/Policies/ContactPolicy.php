<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any contacts.
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
     * Determine whether the user can view the contact.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contact  $contact
     * @return mixed
     */
    public function view(User $user, Contact $contact)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create contacts.
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
     * Determine whether the user can update the contact.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contact  $contact
     * @return mixed
     */
    public function update(User $user, Contact $contact)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the contact.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contact  $contact
     * @return mixed
     */
    public function delete(User $user, Contact $contact)
    {
        //
    }

    /**
     * Determine whether the user can restore the contact.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contact  $contact
     * @return mixed
     */
    public function restore(User $user, Contact $contact)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the contact.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contact  $contact
     * @return mixed
     */
    public function forceDelete(User $user, Contact $contact)
    {
        //
    }
}
