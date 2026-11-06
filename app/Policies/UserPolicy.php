<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->isAdminLevel() || $user->isExecutiveLevel()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        if ($user->isAdminLevel()) {
            return true;
        }
        if ($user->isExecutiveLevel() && $model->dealership_id == $user->dealership_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isExecutiveLevel();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->id == $model->id) {
            return true;
        }
        if ($user->isExecutiveLevel() && $model->dealership_id == $user->dealership_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
