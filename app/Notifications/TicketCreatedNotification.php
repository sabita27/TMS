<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketCreatedNotification extends Notification
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'ticket_created',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_id,
            'subject' => $this->ticket->subject,
            'user_name' => $this->ticket->user->name,
            'message' => 'New ticket raised by ' . $this->ticket->user->name . ': ' . $this->ticket->subject,
        ];
    }
}
