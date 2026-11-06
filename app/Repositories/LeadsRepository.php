<?php

namespace App\Repositories;

use App\Actions\StoreManualLead;
use App\Dealership;
use App\Enums\ActivityTypes;
use App\Enums\LeadStatus;
use App\Events\LeadAsignado;
use App\Events\LeadDerivado;
use App\Exceptions\NotifiableException;
use App\Lead;
use App\Metrics\LeadsFilters;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadsRepository
{
    /**
     * Delega por tipo de usuario
     *
     * @return void
     */
    public function getForUser(User $user, LeadsFilters $filters)
    {
        if ($user->isExecutiveLevel()) {
            return $this->getForOwner($user, $filters);
        }
        if ($user->isSeller()) {
            return $this->getForSeller($user, $filters);
        }
        if ($user->isAdminLevel()) {
            return $this->getForAdmin($filters);
        }
    }

    /**
     * Consigue los leads para los owners
     *
     * @return void
     */
    public function getForOwner(User $owner, LeadsFilters $filters)
    {
        $leads = Lead::where('dealership_id', $owner->dealership_id)
            ->with('user')
            ->whereBetween('created_at', [$filters->start, $filters->end])
            ->when($filters->origin, function ($query) use ($filters) {
                return $query->where('origin', $filters->origin);
            })
            ->when($filters->status, function ($query) use ($filters) {
                return $query->where('status', $filters->status);
            })
            ->when($filters->product_category, function ($query) use ($filters) {
                return $query->where('product_category', $filters->product_category);
            })
            ->when($filters->seller, function ($query, $user_id) {
                return $query->where('user_id', $user_id);
            })
            ->get();

        return $leads;
    }

    /**
     * Consigue los leads para los sellers
     *
     * @param  User  $owner
     * @return void
     */
    public function getForSeller(User $seller, LeadsFilters $filters)
    {
        $leads = $seller->dealership->leads()
            ->with('user')
            ->whereBetween('created_at', [$filters->start, $filters->end])
            ->when($filters->origin, function ($query) use ($filters) {
                return $query->where('origin', $filters->origin);
            })
            ->when($filters->status, function ($query) use ($filters) {
                return $query->where('status', $filters->status);
            })
            ->when($filters->product_category, function ($query) use ($filters) {
                return $query->where('product_category', $filters->product_category);
            })
            ->where(function ($query) use ($seller) {
                $query->where('user_id', $seller->id)
                    ->orWhere('user_id', null);
            })
            ->get();

        return $leads;
    }

    /**
     * @return void
     */
    public function getForAdmin(LeadsFilters $filters)
    {
        $kam_dealerships_id = $this->getKamDealershipsId($filters->kam_id);

        return Lead::with('dealership.kam', 'folios')
            ->whereBetween('created_at', [$filters->start, $filters->end])
            ->when($filters->brand, function ($query, $brand) {
                return $query->where('brand', $brand);
            })
            ->when($filters->origin, function ($query, $origin) {
                return $query->where('origin', $origin);
            })
            ->when($filters->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($filters->product_category, function ($query) use ($filters) {
                return $query->where('product_category', $filters->product_category);
            })
            ->when($filters->dealership_id, function ($query, $dealership_id) {
                return $query->where('dealership_id', $dealership_id);
            })
            ->when($filters->kam_id, function ($query) use ($kam_dealerships_id) {
                return $query->whereIn('dealership_id', $kam_dealerships_id);
            })
            ->get();
    }

    /**
     * La cuenta segun filtros de todos los leads
     *
     * @return void
     */
    public function getCount(LeadsFilters $filters)
    {
        $kam_dealerships_id = $this->getKamDealershipsId($filters->kam_id);

        return Lead::whereBetween('created_at', [$filters->start, $filters->end])
            ->when($filters->brand, function ($query, $brand) {
                return $query->where('brand', $brand);
            })
            ->when($filters->origin, function ($query, $origin) {
                return $query->where('origin', $origin);
            })
            ->when($filters->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($filters->kam_id, function ($query) use ($kam_dealerships_id) {
                return $query->whereIn('dealership_id', $kam_dealerships_id);
            })
            ->when($filters->product_category, function ($query, $product_category) {
                return $query->where('product_category', $product_category);
            })
            ->when($filters->dealership_id, function ($query, $dealership_id) {
                return $query->where('dealership_id', $dealership_id);
            })
            ->count();
    }

    /**
     * La cuenta de todos los estatus para los adminleven
     *
     * @param  int  $status
     * @return void
     */
    public function getCountForStatus(LeadsFilters $leadsFilters): array
    {
        $count = [];
        $array =  LeadStatus::getArray();

        foreach (LeadStatus::getArray() as $name => $value) {
            $leadsFilters->status = $value;
            $count[$name] = $this->getCount($leadsFilters);
        }

        return $count;
    }

    /**
     * La cuenta de todos los estatus para los usuarios
     *
     * @param  int  $status
     * @param  LeadsFilters  $leadsFilters
     * @return void
     */
    public function getCountForAllStatusByUser(User $user, LeadsFilters $filters): array
    {
        $count = [];
        foreach (LeadStatus::getArray() as $name => $value) {
            $count[$name] = $this->getCountForStatusByUser($value, $user, $filters);
        }

        return $count;
    }

    /**
     * La cuenta para mostrarle al usuarios de distribuidores en las tabs por estado
     *
     * @return void
     */
    public function getCountForStatusByUser($status, User $user, LeadsFilters $filters)
    {
        $leads = $user->dealership->leads()
            ->with('user')
            ->whereBetween('created_at', [$filters->start, $filters->end])
            ->when($filters->origin, function ($query) use ($filters) {
                return $query->where('origin', $filters->origin);
            })
            ->when($filters->seller, function ($query, $user_id) {
                return $query->where('user_id', $user_id);
            })
            ->when($filters->product_category, function ($query, $product_category) {
                return $query->where('product_category', $product_category);
            })
            ->where(function ($query) use ($user) {
                // Si es vendedor cuenta solo los que el puede contactar
                if ($user->isSeller()) {
                    $query->where('user_id', $user->id)
                        ->orWhere('user_id', null);
                }
            })
            ->where('status', $status)
            ->count();

        return $leads;
    }

    /**
     * Asigna el vendedor al lead
     *
     * @return void
     */
    public function assignLeadToUser(Lead $lead, $user_id)
    {
        // Si vino sin usuario es para liberar
        if ($user_id == null) {
            $this->releaseLead($lead);

            return true;
        }
        // Asigna
        $seller = User::find($user_id);
        $update = $lead->update(['user_id' => $user_id]);

        if (! $update) {
            throw new NotifiableException('Hubo un problema actualizando el lead');

            return false;
        }
        // Crea la actividad de asignacion
        $lead->addActivity([
            'user_id' => Auth::user()->id,
            'type' => ActivityTypes::Asignar,
            'observation' => __('messages.leads.asignado', ['vendedor' => $seller->name]),
        ]);

        event(new LeadAsignado($lead));

        return true;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function take(Lead $lead, User $user)
    {
        $update = $lead->update(['user_id' => $user->id]);

        if (! $update) {
            throw new NotifiableException('Hubo un problema actualizando el lead');
        }
        // Crea la actividad de tomar
        $lead->addActivity([
            'user_id' => $user->id,
            'type' => ActivityTypes::Tomar,
            'observation' => __('messages.leads.tomado', ['vendedor' => $user->name]),
        ]);

        return true;
    }

    /**
     * Libera el lead
     *
     * @return void
     */
    public function releaseLead(Lead $lead)
    {
        $lead->update(['user_id' => null]);
        $data = [
            'type' => ActivityTypes::Comentario,
            'user_id' => Auth::user()->id,
            'lead_id' => $lead->id,
            'observation' => __('messages.leads.liberado'),
        ];
        $lead->addActivity($data);
    }

    /**
     * Clusura el lead
     *
     * @return void
     */
    public function closeLead(Lead $lead, Request $request)
    {
        $data = [
            'type' => ActivityTypes::Clausurar,
            'user_id' => Auth::user()->id,
            'lead_id' => $lead->id,
            'observation' => $request->get('observation'),
        ];
        $lead->addActivity($data);
    }

    /**
     * Clusura el lead
     */
    public function discardLead(Lead $lead, Request $request)
    {
        $data = [
            'type' => ActivityTypes::Descartar,
            'user_id' => Auth::user()->id,
            'lead_id' => $lead->id,
            'reason' => $request->get('reason'),
            'observation' => $request->get('observation'),
        ];

        return $lead->addActivity($data);
    }

    /**
     * Deriva el lead desde el formulario de derivar en el panel de administracion
     *
     * @param  strin|null  $observation
     * @return void
     */
    public function deriveToDealershipFromAdmin(Lead $lead, Dealership $dealership, $observation)
    {
        $lead->update(['dealership_id' => $dealership->id]);
        $base_observation = Auth::user()->name.' derivó este prospecto al distribuidor '.$dealership->name;
        if ($observation) {
            $observation = $base_observation.' con la siguiente observación: '.$observation;
        } else {
            $observation = $base_observation;
        }

        $activity_data = [
            'type' => ActivityTypes::Derivar,
            'user_id' => Auth::user()->id,
            'observation' => $observation,
        ];
        $lead->addActivity($activity_data);

        // dispara el evento
        event(new LeadDerivado($lead));

        // Crea o asocia el contacto si ya existe
        $this->createOrAsociateContactFromLead($lead, $dealership);
    }

    /**
     * Derivacion del lead desde la creacion manual del lead con indicacion de dealershite
     * Puede ser por cualquier tipo de usuario. Solo si se coloco el dealership_id en el
     * formulario se llega a este metodo, por eso no hay que actualizar el lead,
     * se crea con el dealership_id de una vez
     *
     * @return void
     */
    public function deriveToDealershipFromManualForm(Lead $lead, Dealership $dealership)
    {
        $activity_data_for_deriviar = [
            'type' => ActivityTypes::Derivar,
            'user_id' => Auth::user()->id,
            'observation' => __('messages.leads.derivado_manual'),
        ];
        $lead->addActivity($activity_data_for_deriviar);

        // Si es desde un admnin el que registro el lead, envia notificacion a los owners
        if (Auth::user()->isAdmin()) {
            event(new LeadDerivado($lead));
        }
        // Crea o asocia el contacto si ya existe
        $this->createOrAsociateContactFromLead($lead, $dealership);
    }

    /**
     * Deriva el lead que llego desde el web service con dealership registrado.
     *
     * @return void
     */
    public function deriveToDealershipFromWebService(Lead $lead, Dealership $dealership)
    {
        $lead->update(['dealership_id' => $dealership->id]);
        $activity_data = [
            'type' => ActivityTypes::Derivar,
            'observation' => __('messages.leads.derivado_web'),
        ];
        $lead->addActivity($activity_data);
        // Crea o asocia el contacto si ya existe
        $this->createOrAsociateContactFromLead($lead, $dealership);
    }

    /**
     * Store the lead from the app
     *
     * @return void
     */
    public function storeManual(Request $request)
    {
        $action = new StoreManualLead;
        $lead = $action->handle($request);

        return $lead;
    }

    /**
     * Crea o asocia el contacto si es que existe
     *
     * @return void
     */
    protected function createOrAsociateContactFromLead(Lead $lead, Dealership $dealership)
    {
        $contactRepo = new ContactsRepository;
        $contactRepo->asociateContactFromLead($lead, $dealership);
    }

    /**
     * Consigue los id de los dealerships del kam seleccionado
     * si es que viene en los filtros
     *
     * @param [type] $kam_id
     */
    protected function getKamDealershipsId($kam_id): array
    {
        if (! $kam_id) {
            return [];
        }

        return DB::table('dealerships')
            ->where('kam_id', $kam_id)
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();
    }
/*
    public static function getArray(): array // Cambié el nombre a getArray
    {
        $array = [];
        foreach (self::getkeys() as $key) {
            $array[$key] = (self::getValue($key));
        }
        return $array;
    }*/
}
