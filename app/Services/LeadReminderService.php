<?php

namespace App\Services;

use App\Models\LeadFollowup;
use App\Models\Setting;
use App\Services\Integration\TwilioService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\PhoneNumberService;

class LeadReminderService
{
    public function sendDueReminders(int $windowMinutes = null): array
    {
        $windowMinutes = $windowMinutes ?? config('lead_reminders.window_minutes', 60);
        $now = Carbon::now();
        $end = $now->copy()->addMinutes($windowMinutes);

        $followups = LeadFollowup::with(['lead.assignee'])
            ->scheduled()
            ->where(function ($query) use ($now, $end) {
                $query->whereBetween('followup_date', [$now->toDateString(), $end->toDateString()]);
            })
            ->where(function ($query) {
                $query->whereNull('reminder_sent_at')
                    ->orWhere('reminder_status', '!=', 'sent');
            })
            ->get();

        $results = [
            'processed' => $followups->count(),
            'sent' => 0,
            'failed' => 0,
        ];

        foreach ($followups as $followup) {
            try {
                $this->sendReminderForFollowup($followup);
                $results['sent']++;
            } catch (\Throwable $throwable) {
                $results['failed']++;
                Log::error('Failed to send followup reminder', [
                    'followup_id' => $followup->id,
                    'error' => $throwable->getMessage(),
                ]);
            }
        }

        return $results;
    }

    public function sendReminderForFollowup(LeadFollowup $followup): void
    {
        $lead = $followup->lead;
        if (!$lead) {
            throw new \RuntimeException('Lead not found for followup.');
        }

        $channels = config('lead_reminders.channels', ['email']);

        foreach ($channels as $channel) {
            try {
                match ($channel) {
                    'email' => $this->sendEmailReminder($followup),
                    'sms' => $this->sendSmsReminder($followup),
                    'whatsapp' => $this->sendWhatsAppReminder($followup),
                    default => null,
                };
            } catch (\Throwable $throwable) {
                Log::warning("Followup reminder via {$channel} failed", [
                    'followup_id' => $followup->id,
                    'error' => $throwable->getMessage(),
                ]);
            }
        }

        $followup->update([
            'reminder_status' => 'sent',
            'reminder_sent_at' => now(),
            'reminder_attempts' => $followup->reminder_attempts + 1,
            'reminder_channel' => implode(',', $channels),
        ]);
    }

    protected function sendEmailReminder(LeadFollowup $followup): void
    {
        $lead = $followup->lead;
        $recipient = $lead->email ?? $lead->assignee?->email;

        if (!$recipient) {
            return;
        }

        $subject = config('lead_reminders.email.subject', 'Lead Followup Reminder');
        $fromName = config('lead_reminders.email.from_name', config('app.name'));

        $body = sprintf(
            "Hi %s,\n\nThis is a reminder that you have a followup scheduled for %s at %s.\n\nNotes: %s\n\nThanks,\n%s",
            $lead->assignee?->name ?? $lead->name,
            $followup->followup_date?->format('d M Y'),
            $followup->followup_time ?? 'Anytime',
            $followup->notes ?? 'N/A',
            $fromName
        );

        Mail::raw($body, function ($message) use ($recipient, $subject, $fromName) {
            $message->to($recipient)
                ->subject($subject)
                ->from(config('mail.from.address'), $fromName);
        });
    }

    protected function sendSmsReminder(LeadFollowup $followup): void
    {
        $lead = $followup->lead;
        $phone = $lead->phone ?? $lead->assignee?->phone;

        if (!$phone) {
            return;
        }

        $twilioConfig = Setting::getValue('integration_sms_twilio', []);
        if (empty($twilioConfig['enabled'])) {
            return;
        }

        $message = sprintf(
            'Reminder: Followup with %s on %s at %s.',
            $lead->name,
            $followup->followup_date?->format('d M Y'),
            $followup->followup_time ?? 'Anytime'
        );

        app(TwilioService::class)->sendMessage(
            $twilioConfig,
            'sms',
            PhoneNumberService::normalize($phone, $lead->phone_country_code)['phone'],
            $message
        );
    }

    protected function sendWhatsAppReminder(LeadFollowup $followup): void
    {
        $lead = $followup->lead;
        $phone = $lead->phone ?? $lead->assignee?->phone;

        if (!$phone) {
            return;
        }

        $twilioConfig = Setting::getValue('integration_sms_twilio', []);
        if (empty($twilioConfig['enabled'])) {
            return;
        }

        $message = sprintf(
            'WhatsApp reminder: followup with %s on %s at %s.',
            $lead->name,
            $followup->followup_date?->format('d M Y'),
            $followup->followup_time ?? 'Anytime'
        );

        app(TwilioService::class)->sendMessage(
            $twilioConfig,
            'whatsapp',
            PhoneNumberService::normalize($phone, $lead->phone_country_code)['phone'],
            $message
        );
    }
}

