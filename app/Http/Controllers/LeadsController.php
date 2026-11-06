<?php

namespace App\Http\Controllers;

use App\Enums\LeadStatus;
use App\Exports\LeadExport;
use App\Lead;
use App\Metrics\LeadsFilters;
use App\Repositories\LeadsRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LeadsController extends Controller
{
    protected $leadsRepository;

    public function __construct(LeadsRepository $leadsRepository)
    {
        $this->leadsRepository = $leadsRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->leadsRepository->storeManual($request);
        $this->notifySuccess('Todo bien, se creó el lead');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function discard(Request $request, Lead $lead)
    {
        $this->leadsRepository->discardLead($lead, $request);
        $this->notifySuccess('Se descartó el lead');
        $updated_lead = Lead::find($lead->id);

        // Cuando el vendedor descarta y se rederiva no puede ver el lead
        if ($updated_lead->status == LeadStatus::Descartado && ! $request->user()->isAdminLevel()) {
            return redirect()->route('seller.leads');
        }

        return redirect()->back();
    }

    /**
     * Exportar a excel
     *
     * @return void
     */
    public function export(Request $request)
    {
        $leadsFilters = new LeadsFilters($request->all());
        $leadExport = new LeadExport($leadsFilters, $request->user());

        return Excel::download($leadExport, 'prospectos.xlsx');
    }
}
