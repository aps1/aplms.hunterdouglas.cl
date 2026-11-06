<?php

namespace App\Listeners;

use App\Events\LeadAsignado;
use App\Notifications\LeadAsignadoNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyLeadAsignado implements ShouldQueue
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
    public function handle(LeadAsignado $event)
    {
        $notifiables = $this->notifiebles($event);
        Notification::send($notifiables, new LeadAsignadoNotification($event->lead));
    }

    /**
     * Devuelve los usuarios a notificar segun el lead
     * no incluye al que lo asigno si se autoasigno
     *
     * @return object
     */
    protected function notifiebles($event)
    {
        return User::where('id', $event->lead->user_id)->where('id', '!=', $event->creator->id)->get();
    }
}
