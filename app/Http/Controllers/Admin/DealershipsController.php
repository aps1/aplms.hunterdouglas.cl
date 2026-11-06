<?php

namespace App\Http\Controllers\Admin;

use App\Dealership;
use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Repositories\DealershipsRepository;
use App\User;
use Illuminate\Http\Request;

class DealershipsController extends Controller
{
    protected $dealershipsRepository;

    public function __construct(DealershipsRepository $dealershipsRepository)
    {
        $this->dealershipsRepository = $dealershipsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dealerships = Dealership::all();
        $kams = User::kams()->get();

        return view('admin.dealerships.index', compact('dealerships', 'kams'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Dealership $dealership)
    {
        $range = $this->getDateRangeFromRequest($request);
        $from = $range['from'];
        $to = $range['to'];

        $users = $dealership->users()->get();
        $kams = User::active()->kams()->get();
        $user_types = UserTypes::toSelectArray();
        unset($user_types[UserTypes::Admin]);
        unset($user_types[UserTypes::Kam]);

        $leads = $dealership->leads()
            ->with('user')
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->orderBy('created_at', 'DESC')
            ->get();
        // $metrics_repo = new MetricsRepository($from->format('d-m-Y'), $to->format('d-m-Y'));
        // $quotations_metrics = $metrics_repo->getQuotationsMetrisForModel($dealership);

        return view('admin.dealerships.show')->with([
            'dealership' => $dealership,
            'users' => $users,
            'kams' => $kams,
            'user_types' => $user_types,
            // 'quotations_metrics' => $quotations_metrics,
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
    public function update(Request $request, Dealership $dealership)
    {
        $this->authorize('update', $dealership);
        $this->dealershipsRepository->update($dealership, $request->all());
        $this->notifySuccess('Se actualizó el distribuidor', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Dealership::class);
        $this->dealershipsRepository->create($request->all());
        $this->notifySuccess('Se creó el distribuidor', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate(Request $request, Dealership $dealership)
    {
        $this->authorize('update', $dealership);
        $dealership->inactivate();
        $this->notifySuccess('Se inactivo el concesionario', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, Dealership $dealership)
    {
        $this->authorize('update', $dealership);
        $dealership->activate();
        $this->notifySuccess('Se activó el concesionario', 'Todo bien');

        return redirect()->back();
    }
}
