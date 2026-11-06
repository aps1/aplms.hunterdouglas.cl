<?php

namespace App\Mail;

use App\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadDerivadoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Lead
     *
     * @param  Lead  $lead
     */
    public $lead;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Prospecto Derivado')
            ->markdown('emails.leads.derivado', [
                'lead_id' => $this->lead->id,
                'lead_name' => $this->lead->name,
                'lead_phone' => $this->lead->phone,
                'lead_email' => $this->lead->email,
                'lead_message' => $this->lead->message,
                'lead_web_identifier' => $this->lead->web_identifier,
                'url' => route('seller.leads.show', $this->lead),
            ]);
    }
}
