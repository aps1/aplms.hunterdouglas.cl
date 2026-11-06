<?php

namespace App\Channels;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class WhatsappChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);

        $localization = $notifiable->localization;
        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = config("services.twilio.{$localization}.whatsapp_from");

        $twilio = new Client(config("services.twilio.{$localization}.sid"), config("services.twilio.{$localization}.token"));

        try {
            $send = $twilio->messages->create('whatsapp:'.$to, [
                'from' => 'whatsapp:'.$from,
                'body' => $message->content,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage().' Mensaje: '.$message->content.' To: '.$to);
            $send = false;
        }

        return $send;
    }
}
