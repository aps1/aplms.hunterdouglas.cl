<?php

namespace App\Http\Controllers\Seller;

use App\Enums\DiscardReasons;
use App\Enums\LeadOrigins;
use App\Enums\LeadPriority;
use App\Enums\LeadSources;
use App\Enums\LeadStatus;
use App\Enums\ProductCategory;
use App\Http\Controllers\Controller;
use App\Lead;
use App\Metrics\LeadsFilters;
use App\Repositories\BrandsRepository;
use App\Repositories\LeadsRepository;
use App\User;
use App\Workflows\LeadWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadsController extends Controller
{
    protected $leadsRepository;

    protected $brandsRepository;

    public function __construct(LeadsRepository $leadsRepository, BrandsRepository $brandsRepository)
    {
        $this->leadsRepository = $leadsRepository;
        $this->brandsRepository = $brandsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filters = new LeadsFilters($request->all());
        $leads = $this->leadsRepository->getForUser($user, $filters);
        $brands = $this->brandsRepository->getForDealership($user->dealership);
        $sellers = $user->dealership->users;
        $leads_count_for_status = $this->leadsRepository->getCountForAllStatusByUser($user, $filters);

        return view('seller.leads.index')->with([
            'leads' => $leads,
            'origin' => $request->get('origin'),
            'status' => $request->get('status'),
            'product_category' => $request->get('product_category'),
            'from' => $filters->start->format('d-m-Y'),
            'to' => $filters->end->format('d-m-Y'),
            'manual_origins' => LeadOrigins::onlyManualOrigins(),
            'origins' => LeadOrigins::toSelectArray(),
            'sources' => LeadSources::toSelectArray(),
            'states' => LeadStatus::toSelectArrayForUserType($request->user()),
            'product_categories' => ProductCategory::toSelectArray(),
            'seller' => $user,
            'selected_seller' => $request->get('seller'),
            'sellers' => $sellers,
            'brands' => $brands,
            'leads_count_for_status' => $leads_count_for_status,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lead  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        $seller = Auth::user();
        $this->authorize('view', $lead);
        $lead->load(['activities.user', 'activities.media', 'activities.media.model.lead']);
        $sellers = User::where('dealership_id', $seller->dealership_id)->active()->get();
        $discard_reasons = DiscardReasons::toSelectArray();

        // si tiene usuario filtra los vendedores para el select de reasignar
        if ($lead->user_id) {
            $sellers = $sellers->filter(function ($seller) use ($lead) {
                return $seller->id != $lead->user_id;
            });
        }

        $leadWorkflow = new LeadWorkflow;
        $activities_types = $leadWorkflow->getActivitiesForLead($lead);

        $priority_types = LeadPriority::toSelectArray();
        $open_leads = $seller->open_leads()->get();

        return view('seller.leads.show')->with([
            'lead' => $lead,
            'seller' => $seller,
            'sellers' => $sellers,
            'activities_types' => $activities_types,
            'priority_types' => $priority_types,
            'discard_reasons' => $discard_reasons,
            'open_leads' => $open_leads,
            'url_previus' => url()->previous(),
        ]);
    }

    /**
     * Take the lead
     *
     * @return void
     */
    public function take(Lead $lead)
    {
        $this->authorize('take', $lead);
        $this->leadsRepository->take($lead, Auth::user());
        $this->notifySuccess('Se registró la actividad', 'Todo bien');

        return redirect()->route('seller.leads.show', $lead);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function print(Quotation $quotation, Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function send(Quotation $quotation) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quotation $quotation) {}

    /**
     * Validate store from seller and manager
     *
     * @return void
     */
    protected function validateStore(Request $request) {}
}
