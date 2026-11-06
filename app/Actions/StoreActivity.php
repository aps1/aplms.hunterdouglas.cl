<?php

namespace App\Actions;

use App\Activity;
use App\Enums\ActivityTypes;
use App\Enums\ContactTypes;
use App\Enums\DiscardReasons;
use App\Enums\LeadStatus;
use App\Folio;
use App\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreActivity
{
    protected $data;

    protected $lead;

    /**
     * Las observaciones que se pueden generar en el sistema
     *
     * @var string|null
     */
    protected $observations = null;

    /**
     * handle the request
     */
    public function handle(Lead $lead, array $data)
    {
        $this->lead = $lead;
        $this->data = $data;

        // Si cotizo agrega net value al lead
        if ($this->data['type'] == ActivityTypes::Cotizar) {
            $lead->update(['net_value' => $this->data['net_value']]);
        }
        // Si es foliar, agrega los folios al lead
        if ($this->data['type'] == ActivityTypes::Foliar) {
            $this->AddFoliosToLead();
        }
        $activity_data = $this->parseActivityData();
        $activity = $lead->activities()->create($activity_data);

        if (isset($this->data['files'])) {
            $this->addFilesToActivity($this->data['files'], $activity);
        }

        // Si se decarto y es rederivable, liberalo para rederivar
        if ($this->leadIsRederivable($activity)) {
            $this->liberaLeadParaRederivar();

            return $activity;
        }

        $this->updateLeadStatusBasedOnActivity($activity);
        $this->updateLeadUserBasedOnActivity($activity);
        $this->updateContactTypeBasedOnActivity($activity);

        return $activity;
    }

    /**
     * Agregar o actualiza los folios desde la actividad folior
     *
     * @return void
     */
    protected function AddFoliosToLead()
    {
        // Elimina los folios del lead
        $this->lead->folios()->delete();
        // el formulario puede tener folios nulos por el dise;o
        foreach ($this->data['folios'] as $folio_number) {
            if (! $folio_number) {
                continue;
            }
            Folio::create([
                'number' => $folio_number,
                'lead_id' => $this->lead->id,
            ]);
            $this->observations .= '<br> -'.$folio_number.' ';
        }
    }

    protected function parseActivityData(): array
    {
        // Concatena la observacion del usuario con la que pueda generar el sistema
        // por ejemplo en el caso de los folios se agregan a la observacion
        if (isset($this->data['observation'])) {
            $this->observations = $this->data['observation'].$this->observations;
        }

        return [
            'type' => $this->data['type'],
            'observation' => $this->observations,
            'user_id' => isset($this->data['user_id']) ? $this->data['user_id'] : null,
            'dealership_id' => isset($this->data['dealership_id']) ? $this->data['dealership_id'] : null,
            'lead_id' => isset($this->data['lead_id']) ? $this->data['lead_id'] : null,
            'reason' => isset($this->data['reason']) ? $this->data['reason'] : null,
            'localization' => $this->lead->localization,
            'previous_at' => $this->findPreviusActivityDateForLead(),
        ];
    }

    /**
     * Consigue la fecha de la ultima actividad
     *
     * @return void
     */
    protected function findPreviusActivityDateForLead()
    {
        $last_activity = $this->lead->activities->last();
        // Si no hay actividad, regresa la creacion del lead
        if (! $last_activity) {
            return $this->lead->created_at;
        }

        return $last_activity->created_at;
    }

    /**
     * @param  Lead  $lead
     * @return void
     */
    protected function updateLeadStatusBasedOnActivity(Activity $activity)
    {
        switch ($activity->type) {
            case ActivityTypes::Derivar:
                $this->lead->update(['status' => LeadStatus::Derivado]);
                break;
            case ActivityTypes::Desiste:
                $this->lead->update(['status' => LeadStatus::Perdido]);
                break;
            case ActivityTypes::Venta:
                $this->lead->update(['status' => LeadStatus::Vendido]);
                break;
            case ActivityTypes::Contacto:
                $this->lead->update(['status' => LeadStatus::Contactado]);
                break;
            case ActivityTypes::Cotizar:
                $this->lead->update(['status' => LeadStatus::Cotizado]);
                break;
            case ActivityTypes::Asignar:
                $this->lead->update(['status' => LeadStatus::Asignado]);
                break;
            case ActivityTypes::Tomar:
                $this->lead->update(['status' => LeadStatus::Asignado]);
                break;
                /* case ActivityTypes::Clausurar:
                $this->lead->update(['status' => LeadStatus::Clausurado]);
                break;*/
            case ActivityTypes::Visitar:
                $this->lead->update(['status' => LeadStatus::Visitado]);
                break;
            case ActivityTypes::Descartar:
                $this->lead->update([
                    'status' => LeadStatus::Descartado,
                    'status_reason' => $this->data['reason'],
                ]);
                break;
                /* case ActivityTypes::Ignorar:
                $this->lead->update(['status' => LeadStatus::NoRequiereAccion]);
                break;*/
            case ActivityTypes::Foliar:
                // Puede haber eliminado los folios y en ese caso vuelve a vendido
                if ($this->lead->folios->count()) {
                    $this->lead->update(['status' => LeadStatus::Foliado]);
                } else {
                    $this->lead->update(['status' => LeadStatus::Vendido]);
                }
                break;
        }
    }

    /**
     * Actualiza el usario segun el tipo de actividad que se agrega
     *
     * @return void
     */
    protected function updateLeadUserBasedOnActivity(Activity $activity)
    {
        // El admin level no toma oportunidades
        if (Auth::check() && Auth::user()->isAdminLevel()) {
            return;
        }

        $activities_that_updates_user = [
            ActivityTypes::Tomar,
            ActivityTypes::Contacto,
            ActivityTypes::Cotizar,
            ActivityTypes::Venta,
            ActivityTypes::Desiste,
        ];
        // cuando se crea desde sitios no hay usuario
        $user_id = Auth::check() ? Auth::user()->id : null;

        if (in_array($activity->type, $activities_that_updates_user)) {
            $this->lead->update(['user_id' => $user_id]);
        }
    }

    /**
     * Si el lead tiene contacto y la actividad fue venta,
     * pon al contacto como cliente
     *
     * @return void
     */
    protected function updateContactTypeBasedOnActivity(Activity $activity)
    {
        if ($this->lead->contact && $activity->type == ActivityTypes::Venta) {
            $this->lead->contact->update(['type' => ContactTypes::Cliente]);
        }

    }

    /**
     * @return void
     */
    protected function addFilesToActivity(array $files, Activity $activity)
    {
        foreach ($files as $file) {
            $activity->addMedia($file)->toMediaCollection();
        }
    }

    /**
     * Si se descarto por distancia u otros motivos hay se puede rederivar
     */
    protected function leadIsRederivable(Activity $activity): bool
    {
        $reasons_to_rederivar = [DiscardReasons::Localizacion, DiscardReasons::Otro];

        return $activity->type == ActivityTypes::Descartar &&
            in_array($activity->reason, $reasons_to_rederivar);
    }

    /**
     * Se libera para que un admin lo rederive
     */
    protected function liberaLeadParaRederivar()
    {
        $this->lead->update([
            'status' => LeadStatus::Descartado,
            'user_id' => null,
            'dealership_id' => null,
        ]);
    }
}
