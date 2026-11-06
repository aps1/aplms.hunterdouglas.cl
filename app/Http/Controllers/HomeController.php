<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->user()->isSeller()) {
            return redirect(route('seller.leads'));
        }
        if ($request->user()->isAdminLevel()) {
            return redirect(route('admin.leads'));
        }
        // owner o manager
        if ($request->user()->isExecutiveLevel()) {
            return redirect(route('seller.leads'));
        }

        return abort('404');
    }
}
