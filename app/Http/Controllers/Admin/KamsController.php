<?php

namespace App\Http\Controllers\Admin;

use App\Dealership;
use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Repositories\UsersRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KamsController extends Controller
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
        $kams = User::kams()->get();
        $dealerships = Dealership::with('kam')->get();

        return view('admin.kams.index')->with([
            'kams' => $kams,
            'dealerships' => $dealerships,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->usersRepository->store($request->all(), UserTypes::Kam);
        $this->notifySuccess(__('messages.kams.sucess_store'), 'Todo bien');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $kam
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $range = $this->getDateRangeFromRequest($request);
        $from = $range['from'];
        $to = $range['to'];

        $activities = $user->activities()->with('lead', 'user')->whereBetween('created_at', [$from, $to])->get()->sortByDesc('id');
        $dealerships = Dealership::active()->with('kam')->orderBy('name')->get();

        return view('admin.kams.show')->with([
            'kam' => $user,
            'dealerships' => $dealerships,
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
        $request->request->add(['type' => UserTypes::Kam]);
        $this->usersRepository->update($request, $user);
        $this->notifySuccess('Se actualizó el Kam', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function dealerships(Request $request, User $user)
    {
        DB::transaction(function () use ($user, $request) {
            // detach all dealers from kam
            DB::table('dealerships')->where('kam_id', $user->id)->update(['kam_id' => null]);
            // Asigna la nueva lista, si viene null, no agrega ninguno
            if (is_array($request->dealerships)) {
                DB::table('dealerships')->whereIn('id', $request->dealerships)->update(['kam_id' => $user->id]);
            }
        });
        $this->notifySuccess(__('messages.kams.sucess_update_dealerships'), 'Todo bien');

        return redirect()->back();
    }

    /**
     * toggle activation.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, User $user)
    {
        if (! $user->is_active) {
            $user->activate();
            $this->notifySuccess('Se activó el usuario', 'Todo bien');
        } else {
            $user->inactivate();
            $this->notifySuccess('Se inactivo el usuario', 'Todo bien');
        }

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
