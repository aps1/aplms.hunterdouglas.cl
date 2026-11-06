<?php

namespace App\Notifications;

use App\Lead;
use App\Mail\LeadAsignadoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeadAsignadoNotification extends Notification
{
    use Queueable;

    public $lead;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new LeadAsignadoMail($this->lead))->to($notifiable->email);
    }
}
