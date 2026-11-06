<?php

namespace App\Http\Controllers;

use App\Enums\UserTypes;
use App\Repositories\UsersRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
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
        $this->authorize('viewAny', User::class);
        if (Auth::user()->isAdminLevel()) {
            $users = $this->usersForAdmin();
        } elseif (Auth::user()->isExecutiveLevel()) {
            $users = $this->usersForOwner(Auth::user());
        }

        return view('users.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $allowedTypes = [UserTypes::Owner, UserTypes::Seller, UserTypes::Manager];
        $type = (int) $request->get('type');
        if (! in_array($type, $allowedTypes, true)) {
            abort(403);
        }

        $data = $request->all();
        if (Auth::user()->isExecutiveLevel()) {
            $data['dealership_id'] = Auth::user()->dealership_id;
        }

        $this->usersRepository->store($data, $type);
        $this->notifySuccess('Se agregó al usuario', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('view', $user);
        $range = $this->getDateRangeFromRequest($request);
        $from = $range['from'];
        $to = $range['to'];

        $activities = $user->activities()->whereBetween('created_at', [$from, $to])->with(['lead', 'user'])->get()->sortByDesc('id');
        $leads = $user->leads()->whereBetween('created_at', [$from, $to])->get()->sortByDesc('id');
        $user_types = UserTypes::toSelectArray();
        unset($user_types[UserTypes::Admin]);

        return view('users.show')->with([
            'user' => $user,
            'user_types' => $user_types,
            'activities' => $activities,
            'leads' => $leads,
            'from' => $from->format('d-m-Y'),
            'to' => $to->format('d-m-Y'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        // Por defecto mantener el tipo actual del usuario editado
        $type = $user->type;

        // Solo Admin o Executive pueden cambiar el rol, y únicamente entre roles de distribuidor
        if ($request->has('type') && (Auth::user()->isAdmin() || Auth::user()->isExecutiveLevel())) {
            $requestedType = (int) $request->get('type');
            $allowedTypes = [UserTypes::Owner, UserTypes::Seller, UserTypes::Manager];
            if (in_array($requestedType, $allowedTypes, true)) {
                $type = $requestedType;
            }
        }

        $request->request->add(['type' => $type]);

        $this->usersRepository->update($request, $user);
        $this->notifySuccess('Se actualizaron los datos', 'Todo bien');

        return redirect()->back();
    }

    /**
     * @return void
     */
    public function inactivate(User $user)
    {
        $this->authorize('update', $user);
        $user->inactivate();
        $this->notifySuccess('Se inactivo el usuario', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(User $user)
    {
        $this->authorize('update', $user);
        // NO si tiene dealer inactivo
        if ($user->dealership && ! $user->dealership->is_active) {
            $this->notifyError('Debe activar al distribuidor antes de activar a sus usuarios');

            return redirect()->back();
        }
        $user->activate();
        $this->notifySuccess('Se activo el usuario', 'Todo bien');

        return redirect()->back();
    }

    /**
     * todas activos menos admins
     *
     * @return void
     */
    protected function usersForAdmin()
    {
        $users = User::with('dealership')->get();
        $users = $users->reject(function ($user) {
            return $user->isAdmin() || $user->isKam();
        });

        return $users;
    }

    /**
     * los del owner
     *
     * @return void
     */
    protected function usersForOwner(User $owner)
    {
        $dealership = $owner->dealership;
        $users = $dealership->users;
        $users->load('dealership');

        return $users;
    }
}
