<?php

namespace App\Events;

use App\Lead;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class LeadAsignado
{
    use Dispatchable, SerializesModels;

    public $lead;

    public $creator;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
        $this->creator = Auth::user();
    }
}
