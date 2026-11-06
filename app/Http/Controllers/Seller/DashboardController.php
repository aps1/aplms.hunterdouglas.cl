<?php

namespace App\Http\Controllers\Seller;

use App\Enums\ActivityTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index(Request $request)
    {
        $seller = $request->user();
        $open_leads = $seller->open_leads()->get();
        $taken_leads = $seller->pending_leads()->get();
        $close_leads = $seller->closed_leads()->get();
        $activity_contact_type = ActivityTypes::Contacto;

        return view('seller.dashboard.index')->with([
            'open_leads' => $open_leads,
            'taken_leads' => $taken_leads,
            'close_leads' => $close_leads,
            'activity_contact_type' => $activity_contact_type,
            'activities_types' => $activity_contact_type,
            'seller' => $seller,
            'events' => collect([]),
        ]);
    }
}
