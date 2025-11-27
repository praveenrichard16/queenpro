<?php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketRepliedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Ticket $ticket,
        protected TicketMessage $message
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $this->message->load('user');
        $isAdmin = $notifiable->is_admin ?? false;
        $senderName = $this->message->user->name ?? 'Support Team';
        
        return [
            'type' => 'ticket_replied',
            'title' => $isAdmin ? 'New Ticket Reply' : 'Ticket Reply Received',
            'message' => $isAdmin 
                ? "New reply on ticket #{$this->ticket->ticket_number} from {$senderName}"
                : "You received a reply on ticket #{$this->ticket->ticket_number}",
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'message_id' => $this->message->id,
            'icon' => 'solar:chat-dots-bold-duotone',
            'icon_color' => 'info',
            'url' => $isAdmin 
                ? route('admin.support.tickets.show', $this->ticket)
                : route('customer.support.tickets.show', $this->ticket),
        ];
    }
}
