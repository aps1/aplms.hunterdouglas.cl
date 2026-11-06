<?php

namespace App\Listeners;

use App\Events\WebLeadRegistered;
use App\Services\WcpLeadService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SyncLeadWithWcp implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(WebLeadRegistered $event)
    {
        $lead = $event->lead;
        $service = new WcpLeadService($lead);
        try {
            $service->syncLeadWithWcp();
        } catch (Exception $e) {
            Log::error('Problema sincronizando leads con wcp '.$e->getMessage());
        }
    }
}
