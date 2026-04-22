<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GroupCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any group categories.
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
     * Determine whether the user can view the group category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupCategory  $groupCategory
     * @return mixed
     */
    public function view(User $user, GroupCategory $groupCategory)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create group categories.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the group category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupCategory  $groupCategory
     * @return mixed
     */
    public function update(User $user, GroupCategory $groupCategory)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the group category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupCategory  $groupCategory
     * @return mixed
     */
    public function delete(User $user, GroupCategory $groupCategory)
    {
        //
    }

    /**
     * Determine whether the user can restore the group category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupCategory  $groupCategory
     * @return mixed
     */
    public function restore(User $user, GroupCategory $groupCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the group category.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GroupCategory  $groupCategory
     * @return mixed
     */
    public function forceDelete(User $user, GroupCategory $groupCategory)
    {
        //
    }
}
