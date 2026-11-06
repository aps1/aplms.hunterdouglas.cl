<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Para el middleware de todos las rutas del crud de admins
     *
     * @return mixed
     */
    public function manageAdmins(User $user)
    {
        return $user->isAdmin();
    }
}
