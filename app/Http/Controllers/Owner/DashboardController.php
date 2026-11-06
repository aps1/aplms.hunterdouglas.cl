<?php

namespace App\Http\Controllers\Owner;

use App\Dealership;
use App\Http\Controllers\Controller;
use App\Metrics\Metrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * @var Metrics
     */
    protected $metrics;

    public function __construct(Metrics $metrics)
    {
        $this->metrics = $metrics;
    }

    /**
     * @return void
     */
    public function index(Request $request)
    {
        $range = $this->getDateRangeFromRequest($request);
        $from = $range['from'];
        $to = $range['to'];

        $dealership_id = Auth::user()->dealership_id;
        $seller_id = $request->get('seller');
        $dealership = Dealership::find($dealership_id);
        $sellers = $dealership->users;
        $request->merge(['dealership_id' => $dealership_id]);

        $leads_count_by_status = $this->metrics->getLeadsCountByStatus($request);
        $last_months = $this->metrics->getLastMonths();

        return view('owner.dashboard.index')->with([
            'sellers' => $sellers,
            'seller_id' => $seller_id,
            'last_months' => $last_months,
            'leads_count_by_status' => $leads_count_by_status,
            'from' => $from->format('d-m-Y'),
            'to' => $to->format('d-m-Y'),
        ]);
    }
}
