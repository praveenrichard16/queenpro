<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Ticket $ticket,
        protected ?TicketMessage $latestMessage = null
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ticket Update: ' . $this->ticket->ticket_number)
            ->view('emails.tickets.ticket_updated', [
                'ticket' => $this->ticket,
                'latestMessage' => $this->latestMessage,
            ]);
    }
}

