<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketStatusNotification extends Notification
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
            'type' => 'status_updated',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_id,
            'status' => $this->ticket->status,
            'message' => 'Your ticket #' . $this->ticket->ticket_id . ' status updated to: ' . strtoupper($this->ticket->status),
        ];
    }
}
