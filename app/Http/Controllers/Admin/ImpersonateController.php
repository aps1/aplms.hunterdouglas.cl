<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function take(User $user)
    {
        $super_admins = [
            'contacto@s1.cl',
        ];
        if (in_array(Auth::user()->email, $super_admins)) {
            Auth::login($user);

            return redirect()->route('base');
        }

        return response('', 404);
    }

    public function leave()
    {
        Auth::logout();

        return redirect()->route('base');
    }
}
