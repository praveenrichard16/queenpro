<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Notifications\Tickets\TicketCreatedNotification;
use App\Notifications\Tickets\TicketUpdatedNotification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TicketObserver
{
    public function created(Ticket $ticket): void
    {
        $this->sendTeamNotification(new TicketCreatedNotification($ticket));

        if ($this->shouldSendCustomerUpdates() && $ticket->customer && $ticket->customer->email) {
            $ticket->customer->notify(new TicketUpdatedNotification($ticket));
        }
    }

    public function updated(Ticket $ticket): void
    {
        if (!$ticket->wasChanged(['status', 'assigned_to', 'priority'])) {
            return;
        }

        $latestMessage = $ticket->messages()->latest()->first();

        $this->sendTeamNotification(new TicketUpdatedNotification($ticket, $latestMessage));

        if ($this->shouldSendCustomerUpdates() && $ticket->customer && $ticket->customer->email) {
            $ticket->customer->notify(new TicketUpdatedNotification($ticket, $latestMessage));
        }
    }

    protected function sendTeamNotification($notification): void
    {
        if (!Config::get('support.email.enabled', true)) {
            return;
        }

        $addresses = Config::get('support.email.notify_addresses');

        if (!$addresses) {
            return;
        }

        $emails = collect(Str::of($addresses)->explode([',', ';']))
            ->map(fn ($email) => trim((string) $email))
            ->filter(function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return;
        }

        foreach ($emails as $email) {
            Notification::route('mail', $email)->notify($notification);
        }
    }

    protected function shouldSendCustomerUpdates(): bool
    {
        return Config::get('support.email.send_customer_updates', true);
    }
}

