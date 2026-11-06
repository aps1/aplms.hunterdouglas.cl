<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Dealership;
use App\Enums\ContactSources;
use App\Enums\ContactTypes;
use App\Exports\ContactExport;
use App\Metrics\ContactsFilters;
use App\Repositories\ContactsRepository;
use App\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ContactsController extends Controller
{
    /**
     * @var [type]
     */
    protected $contactsRepo;

    public function __construct(?ContactsRepository $contactsRepo = null)
    {
        $this->contactsRepo = $contactsRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $range = $this->getDateRangeFromRequest($request);
        $contact_types = ContactTypes::toSelectArray();
        $contact_sources = ContactSources::toSelectArray();
        $dealerships = Dealership::with('users')
            ->when($request->get('kam'), function ($query, $kam_id) {
                return $query->where('kam_id', $kam_id);
            })->orderBy('name')->get();
        $kams = User::Kams()->get();

        // Los usuarios de distribuidores solo ven sus contactos
        $selected_dealership = $request->user()->dealership_id ?? $request->get('dealership');
        $selected_kam = $request->user()->isAdmin() ? $request->get('kam') : '';
        $data = [
            'contact_types' => $contact_types,
            'selected_type' => $request->get('type'),
            'contact_sources' => $contact_sources,
            'selected_source' => $request->get('source'),
            'dealerships' => $dealerships,
            'selected_dealership' => $selected_dealership,
            'selected_kam' => $selected_kam,
            'kams' => $kams,
            'from' => $range['from']->format('d-m-Y'),
            'to' => $range['to']->format('d-m-Y'),
        ];
        if (! $request->user()->isAdminLevel()) {
            unset($data['dealerships']);
        }

        return view('contacts.index')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->contactsRepo->storeFromRequest($request);
        $this->notifySuccess(__('messages.contacts.success_store'));

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);
        $contact_types = ContactTypes::toSelectArray();
        $contact_sources = ContactSources::toSelectArray();

        return view('contacts.show')->with([
            'contact_types' => $contact_types,
            'contact_sources' => $contact_sources,
            'contact' => $contact,
            'leads' => $contact->leads,
            'activities' => $contact->getActivities(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $this->authorize('update', $contact);
        $this->contactsRepo->updateFromRequest($contact, $request);
        $this->notifySuccess('Se actualizó el contacto', 'Todo bien');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);
        // Elimina la relacion
        $contact->leads->each(function ($lead) {
            $lead->update(['contact_id' => null]);
        });
        $contact->delete();
        $this->notifySuccess('Se eliminó el contacto', 'Todo bien');

        return redirect(route('contacts'));
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function datatable(Request $request)
    {
        $range = $this->getDateRangeFromRequest($request);
        $dealership_id = $request->user()->dealership_id ?? $request->get('dealership');
        $kam_id = $request->get('kam');
        $kam_dealerships_id = null;
        if ($kam_id) {
            $kam_dealerships_id = DB::table('dealerships')
                ->where('kam_id', $kam_id)
                ->select('id')
                ->get()
                ->pluck('id')
                ->toArray();
        }
        $contacts = Contact::with('dealership')
            ->whereBetween('created_at', [$range['from'], $range['to']])
            ->when($dealership_id, function ($query, $dealership_id) {
                return $query->where('dealership_id', $dealership_id);
            })
            ->when($kam_id, function ($query) use ($kam_dealerships_id) {
                return $query->whereIn('dealership_id', $kam_dealerships_id);
            })
            ->when($request->get('type'), function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->get('source'), function ($query, $source) {
                return $query->where('source', $source);
            })
            ->get();

        return DataTables::of($contacts)
            ->editColumn('name', function ($contact) {
                $route = route('contacts.show', $contact);
                $link = '<a href="'.$route.'" style="color:inherit">'.$contact->name.'</a> ';

                return $link;
            })
            ->editColumn('dealership', function ($contact) use ($request) {
                if (! $request->user()->isAdmin()) {
                    return $contact->dealership->name;
                }
                $route = route('admin.dealerships.show', $contact->dealership_id);
                $link = '<a href="'.$route.'" style="color:inherit">'.$contact->dealership->name.'</a> ';

                return $link;
            })
            ->addColumn('action', function ($contact) {
                $btn = '<a href="'.route('contacts.show', $contact).'" class="btn-white btn btn-xs">Ver</a>';

                return $btn;
            })
            ->rawColumns([
                'name',
                'dealership',
                'action',
            ])
            ->make(true);
    }

    /**
     * Exportar a excel
     *
     * @return void
     */
    public function export(Request $request)
    {
        $filters = $request->all();
        $filters['dealership_id'] = $request->user()->dealership_id ?? $request->get('dealership');
        $contactsFilters = new ContactsFilters($filters);
        $contactExport = new ContactExport($contactsFilters);

        return Excel::download($contactExport, 'contactos.xlsx');
    }
}
