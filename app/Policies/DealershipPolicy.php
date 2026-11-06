<?php

namespace App\Policies;

use App\Dealership;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealershipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any dealerships.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the dealerships.
     *
     * @return mixed
     */
    public function view(User $user, Dealership $dealerships)
    {
        //
    }

    /**
     * Determine whether the user can create dealerships.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the dealerships.
     *
     * @return mixed
     */
    public function update(User $user, Dealership $dealerships)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the dealerships.
     *
     * @return mixed
     */
    public function delete(User $user, Dealership $dealerships)
    {
        //
    }

    /**
     * Determine whether the user can restore the dealerships.
     *
     * @return mixed
     */
    public function restore(User $user, Dealership $dealerships)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the dealerships.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Dealership $dealerships)
    {
        //
    }
}
