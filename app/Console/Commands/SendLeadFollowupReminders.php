<?php

namespace App\Console\Commands;

use App\Services\LeadReminderService;
use Illuminate\Console\Command;

class SendLeadFollowupReminders extends Command
{
    protected $signature = 'leads:send-followup-reminders {--window=60 : Reminder window in minutes}';

    protected $description = 'Send reminders for upcoming lead followups';

    public function handle(LeadReminderService $service): int
    {
        $window = (int) $this->option('window');

        $this->info("Sending followup reminders (window: {$window} minutes)...");

        $result = $service->sendDueReminders($window);

        $this->info("Processed {$result['processed']} followups. Sent: {$result['sent']}, Failed: {$result['failed']}");

        return Command::SUCCESS;
    }
}

