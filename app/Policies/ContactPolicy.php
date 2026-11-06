<?php

namespace App\Policies;

use App\Contact;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * @param [type] $user
     * @param [type] $ability
     * @return void
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the contact.
     *
     * @return mixed
     */
    public function view(User $user, Contact $contact)
    {
        if ($user->dealership_id === $contact->dealership_id) {
            return true;
        }
        if ($user->isKam()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create contacts.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the contact.
     *
     * @return mixed
     */
    public function update(User $user, Contact $contact)
    {
        if ($user->dealership_id === $contact->dealership_id) {
            return true;
        }
        if ($user->isKam()) {
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the contact.
     * El kam no puede eliminar
     *
     * @return mixed
     */
    public function delete(User $user, Contact $contact)
    {
        return $user->dealership_id === $contact->dealership_id;
    }

    /**
     * Determine whether the user can restore the contact.
     *
     * @return mixed
     */
    public function restore(User $user, Contact $contact)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the contact.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Contact $contact)
    {
        //
    }
}
