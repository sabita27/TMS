<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReplyNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $senderName;
    protected $messageSnippet;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticket, $senderName, $messageSnippet)
    {
        $this->ticket = $ticket;
        $this->senderName = $senderName;
        $this->messageSnippet = $messageSnippet;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_id,
            'subject' => $this->ticket->subject,
            'sender_name' => $this->senderName,
            'message' => $this->messageSnippet,
        ];
    }
}
