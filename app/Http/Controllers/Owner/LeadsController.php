<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Lead;
use App\Repositories\LeadsRepository;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    /**
     * LeadRepo
     *
     * @var [type]
     */
    protected $leadsRepository;

    public function __construct(LeadsRepository $leadsRepository)
    {
        $this->leadsRepository = $leadsRepository;
    }

    /**
     * Asigna al vendedor
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {
        // solo asignar
        if (! $request->has('user_id')) {
            return;
        }
        $this->leadsRepository->assignLeadToUser($lead, $request->get('user_id'));

        $this->notifySuccess('Se asignó el vendedor', 'Todo bien');

        return redirect()->back();
    }
}
