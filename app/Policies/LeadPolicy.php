<?php

namespace App\Policies;

use App\Lead;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any leads.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the lead.
     *
     * @return mixed
     */
    public function view(User $user, Lead $lead)
    {
        // si es manager y es del dealership, permitelo
        if ($user->isExecutiveLevel() && $lead->dealership_id == $user->dealership_id) {
            return true;
        }
        // si la tomo, permitelo
        if ($lead->user_id == $user->id) {
            return true;
        }
        // si no esta tomado, y es del mismo dealership, permitelo
        if (! $lead->isTaken()) {
            if ($lead->dealership_id == $user->dealership_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tomar el lead
     *
     * @return void
     */
    public function take(User $user, Lead $lead)
    {
        // si no esta tomado, y es del mismo dealership, permitelo
        if (! $lead->isTaken()) {
            if ($lead->dealership_id == $user->dealership_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create leads.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the lead.
     *
     * @return mixed
     */
    public function update(User $user, Lead $lead)
    {
        //
    }

    /**
     * Determine whether the user can close the lead.
     *
     * @return mixed
     */
    public function close(User $user, Lead $lead)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the lead.
     *
     * @return mixed
     */
    public function delete(User $user, Lead $lead)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the lead.
     *
     * @return mixed
     */
    public function restore(User $user, Lead $lead)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the lead.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Lead $lead)
    {
        //
    }
}
