<?php

namespace App\Http\Controllers\Admin;

use App\Dealership;
use App\Enums\DiscardReasons;
use App\Enums\LeadOrigins;
use App\Enums\LeadPriority;
use App\Enums\LeadSources;
use App\Enums\LeadStatus;
use App\Enums\ProductCategory;
use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Lead;
use App\Metrics\LeadsFilters;
use App\Repositories\BrandsRepository;
use App\Repositories\LeadsRepository;
use App\User;
use App\Workflows\LeadWorkflow;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class LeadsController extends Controller
{
    protected $leadWorkflow;

    protected $leadsRepository;

    protected $brandsRepository;

    public function __construct(
        LeadWorkflow $leadWorkflow,
        LeadsRepository $leadsRepository,
        BrandsRepository $brandsRepository
    ) {
        $this->leadWorkflow = $leadWorkflow;
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
        Lead::all();
        $range = $this->getDateRangeFromRequest($request);

        $filters = new LeadsFilters($request->all());
        $leads_count = $this->leadsRepository->getCount($filters);
        $leads_count_for_status = $this->leadsRepository->getCountForStatus($filters);

        $dealerships = Dealership::with('users')
            ->when($filters->kam_id, function ($query, $kam_id) {
                return $query->where('kam_id', $kam_id);
            })->orderBy('name')->get();
        $kams = User::Kams()->get();

        return view('admin.leads.index')->with([
            'leads_count' => $leads_count,
            'leads_count_for_status' => $leads_count_for_status,
            'brand' => $request->get('brand'),
            'brands' => $this->brandsRepository->all(),
            'dealership_id' => $request->get('dealership_id'),
            'kam_id' => $request->get('kam_id'),
            'origin' => $request->get('origin'),
            'status' => $request->get('status'),
            'product_category' => $request->get('product_category'),
            'from' => $range['from']->format('d-m-Y'),
            'to' => $range['to']->format('d-m-Y'),
            'origins' => LeadOrigins::toSelectArray(),
            'sources' => LeadSources::toSelectArray(),
            'product_categories' => ProductCategory::toSelectArray(),
            'states' => LeadStatus::toSelectArrayForUserType($request->user()),
            'dealerships' => $dealerships,
            'kams' => $kams,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        $lead->load(['activities.user', 'activities.media']);
        // Delaer activos con owner para el select de derivar
        $dealerships = Dealership::where('is_active', 1)->with(['users' => function ($query) {
            $query->where([
                ['type', UserTypes::Owner],
                ['is_active', 1],
            ]);
        }])->orderBy('name')->get();
        $priority_types = LeadPriority::toSelectArray();
        $discard_reasons = DiscardReasons::toSelectArray();
        $activities_types = $this->leadWorkflow->getActivitiesForLead($lead);
        $brands = $this->brandsRepository->all();

        return view('admin.leads.show')->with([
            'lead' => $lead,
            'priority_types' => $priority_types,
            'activities_types' => $activities_types,
            'dealerships' => $dealerships,
            'discard_reasons' => $discard_reasons,
            'brands' => $brands,
            'url_previus' => url()->previous(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {
        // Actualiza la prioridad
        if ($request->has('priority')) {
            $lead->update(['priority' => $request->get('priority')]);
            $this->notifySuccess(__('messages.leads.success_strategic'));

            return redirect()->back();
        }
    }

    /**
     * Deriva el lead a un distribuidor
     */
    public function derive(Request $request, Lead $lead)
    {
        $validator = Validator::make($request->all(), ['dealership_id' => 'required']);
        if ($validator->fails()) {
            $this->notifyError($validator->errors()->first());

            return redirect()->back();
        }

        $dealership = Dealership::findOrFail($request->get('dealership_id'));
        $this->leadsRepository->deriveToDealershipFromAdmin($lead, $dealership, $request->get('observation'));

        $this->notifySuccess(__('messages.leads.derivado'));
        // regresa a la vista anterior con los filtros, por lo general sera el index de leads
        if ($request->filled('url_previus')) {
            return redirect($request->get('url_previus'));
        }

        return redirect()->back();
    }

    /**
     * Clausura el lead
     *
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request, Lead $lead)
    {
        $this->authorize('close', $lead);
        $this->leadsRepository->closeLead($lead, $request);
        $this->notifySuccess(__('messages.leads.success_clausurar'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);
        $lead->activities()->delete();
        $lead->delete();
        $this->notifySuccess(__('messages.leads.success_eliminar'));

        return redirect()->route('admin.leads');
    }

    /**
     * @return void
     */
    public function datatable(Request $request)
    {
        $range = $this->getDateRangeFromRequest($request);
        $origin = $request->get('origin');
        $product_category = $request->get('product_category');
        $status = $request->get('status');
        $dealership_id = $request->get('dealership_id');
        $kam_id = $request->get('kam_id');
        $kam_dealerships_id = null;
        if ($kam_id) {
            $kam_dealerships_id = DB::table('dealerships')
                ->where('kam_id', $kam_id)
                ->select('id')
                ->get()
                ->pluck('id')
                ->toArray();
        }

        $leads = Lead::with('dealership')->whereBetween('created_at', [$range['from'], $range['to']])
            ->when($origin, function ($query, $origin) {
                return $query->where('origin', $origin);
            })
            ->when($product_category, function ($query, $product_category) {
                return $query->where('product_category', $product_category);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($kam_id, function ($query) use ($kam_dealerships_id) {
                return $query->whereIn('dealership_id', $kam_dealerships_id);
            })
            ->when($dealership_id, function ($query, $dealership_id) {
                return $query->where('dealership_id', $dealership_id);
            })
            ->get();

        return DataTables::of($leads)
            ->editColumn('id', function ($lead) {
                $route = route('admin.leads.show', $lead);
                $link = '<a href="'.$route.'" style="color:inherit">'.$lead->id.'</a>';

                return $link;
            })
            ->editColumn('name', function ($lead) {
                $route = route('admin.leads.show', $lead);
                $strategic_start = $lead->isStrategic() ? '<span title="'.__('Prospecto estratégico').'" class="text-warning fa fa-star"></span>' : '';
                $link = '<a href="'.$route.'" style="color:inherit">'.$strategic_start.' '.$lead->name.'</a> ';

                return $link;
            })
            ->editColumn('email', function ($lead) {
                $link = '<a href="mailto:'.$lead->email.'" style="color:inherit">'.$lead->email.'</a>';

                return $link;
            })
            ->editColumn('dealership_name', function ($lead) {
                if ($lead->dealership) {
                    $route = route('admin.dealerships.show', $lead->dealership_id);
                    $link = '<a href="'.$route.'" style="color:inherit">'.$lead->dealership->name.'</a>';

                    return $link;
                }
                if (! $lead->isDerivable()) {
                    return 'No requiere';
                }
                // Derivar en show
                $route = route('admin.leads.show', $lead);
                $link = '<div class="btn-group"><a href="'.$route.'" class="btn-warning btn btn-xs">Derivar</a>';

                return $link;
            })
            ->editColumn('status_description', function ($lead) {
                $span = '<span class="label label-'.$lead->status_color.'">'.$lead->status_description.'</span>';

                return $span;
            })
            ->editColumn('created_at', function ($quotation) {
                return $quotation->created_at->format('Y-m-d H:i');
            })
            ->addColumn('action', function ($lead) {
                $btn = '<a href="'.route('admin.leads.show', $lead).'" class="btn-white btn btn-xs">Ver</a>';

                return $btn;
            })
            ->rawColumns([
                'id',
                'name',
                'email',
                'dealership_name',
                'status_description',
                'action',
            ])
            ->make(true);
    }
}
