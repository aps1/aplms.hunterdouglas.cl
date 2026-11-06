<?php

namespace App\Http\Controllers\Api;

use App\Events\WebLeadRegistered;
use App\Http\Controllers\Controller;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    /**
     * store
     *
     * @return void
     */
    public function store(Request $request)
    {
        $service = new LeadService;
        $lead = $service->storeLead($request);
        if ($lead) {
            event(new WebLeadRegistered($lead));

            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false, 'error' => 'Error con el lead',
        ], 500);
    }
}
