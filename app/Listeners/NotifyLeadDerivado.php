<?php

namespace App\Listeners;

use App\Events\LeadDerivado;
use App\Lead;
use App\Notifications\LeadDerivadoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyLeadDerivado implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(LeadDerivado $event)
    {
        $lead = $event->lead;
        // notify executives;
        $executives = $this->notifiebles($lead);
        Notification::send($executives, new LeadDerivadoNotification($lead));
    }

    /**
     * Devuelve los usuarios a notificar segun el lead
     *
     * @return object
     */
    protected function notifiebles(Lead $lead)
    {
        return $lead->dealership->executives()->active()->get();
    }
}
