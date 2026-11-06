<?php

namespace App\Events;

use App\Lead;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadDerivado
{
    use Dispatchable, SerializesModels;

    public $lead;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }
}
