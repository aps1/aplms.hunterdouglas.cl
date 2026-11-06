<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Repositories\UsersRepository;
use App\User;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    protected $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = User::active()->admins()->get();
        $admins = $admins->reject(function ($value, $key) {
            // Todos mis correos en los paises
            return substr($value->email, 0, 11) == 'javier.leon';
        });

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->usersRepository->store($request->all(), UserTypes::Admin);
        $this->notifySuccess('Se agregó al administrador', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $range = $this->getDateRangeFromRequest($request);
        $from = $range['from'];
        $to = $range['to'];

        $activities = $user->activities()->with('lead', 'user')->whereBetween('created_at', [$from, $to])->get()->sortByDesc('id');

        return view('admin.admins.show')->with([
            'admin' => $user,
            'from' => $from->format('d-m-Y'),
            'to' => $to->format('d-m-Y'),
            'activities' => $activities,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->request->add(['type' => UserTypes::Admin]);
        $this->usersRepository->update($request, $user);
        $this->notifySuccess('Se actualizó el administrador', 'Todo bien');

        return redirect()->back();
    }

    /**
     * inactivate the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate(Request $request, User $user)
    {
        $user->inactivate();
        $this->notifySuccess('Se inactivo el usuario', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, User $user)
    {
        $user->activate();
        $this->notifySuccess('Se activó el usuario', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
