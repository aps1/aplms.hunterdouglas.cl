<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KamPolicy
{
    use HandlesAuthorization;

    /**
     * Para el middleware de todos las rutas del crud de kams
     *
     * @return mixed
     */
    public function manageKams(User $user)
    {
        return $user->isAdmin();
    }
}
