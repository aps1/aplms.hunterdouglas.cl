<?php

namespace App\Http\Controllers\Admin;

use App\Dealership;
use App\Http\Controllers\Controller;
use App\Metrics\Metrics;
use Illuminate\Http\Request;

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
     * Admin dashboard
     *
     * @return void
     */
    public function index(Request $request)
    {
        $range = $this->getDateRangeFromRequest($request);
        $from = $range['from'];
        $to = $range['to'];

        $dealership_id = $request->get('dealership_id');
        $seller_id = $request->get('seller_id');
        $dealership = Dealership::find($dealership_id);
        if ($dealership) {
            $sellers = $dealership->users;
        } else {
            $sellers = [];
        }

        $dealerships = Dealership::with('leads', 'users')->get();
        $leads_count_by_status = $this->metrics->getLeadsCountByStatus($request);
        $last_months = $this->metrics->getLastMonths();

        return view('admin.dashboard.index')->with([
            'dealerships' => $dealerships,
            'sellers' => $sellers,
            'dealership_id' => $dealership_id,
            'seller_id' => $seller_id,
            'last_months' => $last_months,
            'leads_count_by_status' => $leads_count_by_status,
            'from' => $from->format('d-m-Y'),
            'to' => $to->format('d-m-Y'),
        ]);
    }
}
