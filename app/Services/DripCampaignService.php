<?php

namespace App\Services;

use App\Models\DripCampaign;
use App\Models\DripCampaignRecipient;
use App\Models\DripCampaignStep;
use App\Models\Enquiry;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use App\Services\EmailService;
use App\Services\PhoneNumberService;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DripCampaignService
{
    protected int $retryDelayMinutes = 15;

    public function startCampaignForRecipient(DripCampaign $campaign, string $recipientType, int $recipientId): DripCampaignRecipient
    {
        $existing = DripCampaignRecipient::where('drip_campaign_id', $campaign->id)
            ->where('recipient_type', $recipientType)
            ->where('recipient_id', $recipientId)
            ->whereIn('status', ['pending', 'in_progress'])
            ->first();

        if ($existing) {
            return $existing;
        }

        if (!$this->passesAudienceFilters($campaign, $recipientType, $recipientId)) {
            throw new \Exception('Recipient does not meet the campaign filters.');
        }

        $firstStep = $campaign->steps()->where('is_active', true)->orderBy('step_number')->first();

        if (!$firstStep) {
            throw new \Exception('Campaign has no active steps.');
        }

        $startAt = $this->calculateNextSendAt($campaign, $firstStep, now());

        $recipient = DripCampaignRecipient::create([
            'drip_campaign_id' => $campaign->id,
            'recipient_type' => $recipientType,
            'recipient_id' => $recipientId,
            'current_step' => $firstStep->step_number,
            'status' => 'in_progress',
            'started_at' => now(),
            'next_send_at' => $startAt,
            'retry_count' => 0,
        ]);

        if ($recipient->next_send_at->lte(now())) {
            $this->processRecipient($recipient);
        }

        return $recipient;
    }

    public function processRecipient(DripCampaignRecipient $recipient): void
    {
        if ($recipient->status !== 'in_progress') {
            return;
        }

        if ($recipient->next_send_at && $recipient->next_send_at->isFuture()) {
            return; // Not time to send yet
        }

        $campaign = $recipient->campaign;

        if (!$this->isWithinSendWindow($campaign)) {
            $recipient->update([
                'next_send_at' => $this->alignToSendWindow($campaign, now()),
            ]);
            return;
        }

        $currentStep = $campaign->steps()
            ->where('step_number', $recipient->current_step)
            ->where('is_active', true)
            ->first();

        if (!$currentStep) {
            // Move to next step or complete
            $this->moveToNextStep($recipient);
            return;
        }

        $template = $currentStep->template;
        if (!$template) {
            Log::warning("Drip campaign step has no template", [
                'recipient_id' => $recipient->id,
                'step_number' => $recipient->current_step,
            ]);
            $this->moveToNextStep($recipient);
            return;
        }

        // Get recipient data
        $recipientData = $this->getRecipientData($recipient);
        if (!$recipientData) {
            Log::warning("Recipient not found", [
                'recipient_id' => $recipient->id,
                'recipient_type' => $recipient->recipient_type,
            ]);
            $recipient->update(['status' => 'cancelled']);
            return;
        }

        if (!$this->passesStepConditions($currentStep, $recipientData['model'] ?? null)) {
            $this->moveToNextStep($recipient);
            return;
        }

        // Send message based on channel
        $errors = [];
        $payload = [
            'step' => $recipient->current_step,
            'template_id' => $template->id,
            'channels_attempted' => [],
            'channels_sent' => [],
        ];
        
        try {
            if ($currentStep->channel === 'email' || $campaign->channel === 'both') {
                $payload['channels_attempted'][] = 'email';
                try {
                    $this->sendEmail($template, $recipientData);
                    $payload['channels_sent'][] = 'email';
                } catch (\Exception $e) {
                    $errors[] = 'Email: ' . $e->getMessage();
                    Log::error("Drip campaign email failed", [
                        'recipient_id' => $recipient->id,
                        'step_number' => $recipient->current_step,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($currentStep->channel === 'whatsapp' || $campaign->channel === 'both') {
                $payload['channels_attempted'][] = 'whatsapp';
                try {
                    $this->sendWhatsApp($template, $recipientData);
                    $payload['channels_sent'][] = 'whatsapp';
                } catch (\Exception $e) {
                    $errors[] = 'WhatsApp: ' . $e->getMessage();
                    Log::error("Drip campaign WhatsApp failed", [
                        'recipient_id' => $recipient->id,
                        'step_number' => $recipient->current_step,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if (empty($payload['channels_sent'])) {
                $this->handleStepFailure($recipient, $campaign, $errors);
                return;
            }

            $recipient->update([
                'last_sent_at' => now(),
                'last_error' => !empty($errors) ? implode('; ', $errors) : null,
                'retry_count' => 0,
                'last_step_payload' => $payload,
            ]);

            $this->moveToNextStep($recipient);

        } catch (\Exception $e) {
            Log::error("Failed to send drip campaign message", [
                'recipient_id' => $recipient->id,
                'step_number' => $recipient->current_step,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->handleStepFailure($recipient, $campaign, [$e->getMessage()]);
        }
    }

    protected function moveToNextStep(DripCampaignRecipient $recipient): void
    {
        $campaign = $recipient->campaign;
        $nextStep = $campaign->steps()
            ->where('step_number', '>', $recipient->current_step)
            ->where('is_active', true)
            ->orderBy('step_number')
            ->first();

        if ($nextStep) {
            $recipient->update([
                'current_step' => $nextStep->step_number,
                'next_send_at' => $this->calculateNextSendAt($campaign, $nextStep, now()),
                'retry_count' => 0,
            ]);

            return;
        }

        $recipient->update([
            'status' => 'completed',
            'completed_at' => now(),
            'next_send_at' => null,
            'retry_count' => 0,
        ]);
    }

    protected function getRecipientData(DripCampaignRecipient $recipient): ?array
    {
        $model = match($recipient->recipient_type) {
            'enquiry' => Enquiry::find($recipient->recipient_id),
            'lead' => Lead::find($recipient->recipient_id),
            'customer' => User::find($recipient->recipient_id),
            default => null,
        };

        if (!$model) {
            return null;
        }

        return [
            'name' => $model->name ?? $model->customer_name ?? 'Customer',
            'email' => $model->email ?? $model->customer_email ?? null,
            'phone' => $model->phone ?? $model->customer_phone ?? null,
            'model' => $model,
        ];
    }

    protected function sendEmail($template, array $recipientData): void
    {
        if (empty($recipientData['email'])) {
            return;
        }

        // Replace template variables
        $content = $this->replaceVariables($template->content, $recipientData);
        $subject = $this->replaceVariables($template->subject ?? 'Notification', $recipientData);

        // Use Laravel Mail or your email service
        \Illuminate\Support\Facades\Mail::raw($content, function ($message) use ($recipientData, $subject) {
            $message->to($recipientData['email'])
                ->subject($subject);
        });
    }

    protected function sendWhatsApp($template, array $recipientData): void
    {
        if (empty($recipientData['phone'])) {
            Log::warning("Drip campaign WhatsApp skipped - no phone number", [
                'template_id' => $template->id,
                'recipient' => $recipientData['name'] ?? 'Unknown',
            ]);
            return;
        }

        $config = $this->getWhatsAppConfig();
        
        if (empty($config['enabled']) || empty($config['api_token']) || empty($config['phone_number_id'])) {
            Log::warning("Drip campaign WhatsApp skipped - not configured", [
                'template_id' => $template->id,
            ]);
            throw new \Exception('WhatsApp is not configured. Please configure it in Integration Settings.');
        }

        // Format phone number using PhoneNumberService (automatically adds country code if missing)
        $phoneNumber = null;
        $countryCode = null;
        
        // Try to get country code from the model if available
        if (isset($recipientData['model'])) {
            $model = $recipientData['model'];
            if (isset($model->phone_country_code)) {
                $countryCode = $model->phone_country_code;
            } elseif (isset($model->customer_phone_country_code)) {
                $countryCode = $model->customer_phone_country_code;
            } elseif (isset($model->contact_phone_country_code)) {
                $countryCode = $model->contact_phone_country_code;
            }
        }
        
        $normalized = PhoneNumberService::normalize($recipientData['phone'], $countryCode);
        
        if (empty($normalized['phone'])) {
            Log::warning("Drip campaign WhatsApp skipped - invalid phone number", [
                'template_id' => $template->id,
                'original_phone' => $recipientData['phone'],
            ]);
            throw new \Exception('Invalid phone number format. Phone number must be valid and include country code (e.g., +91XXXXXXXXXX or XXXXXXXXXX with default country code).');
        }
        
        $phoneNumber = PhoneNumberService::formatForWhatsApp($normalized['phone']);

        try {
            // Check if template is a WhatsApp template with template ID
            if ($template->type === 'whatsapp' && !empty($template->whatsapp_template_id) && 
                strtoupper($template->whatsapp_template_status ?? '') === 'APPROVED') {
                // Send as template message
                $this->sendWhatsAppTemplateMessage($config, $phoneNumber, $template, $recipientData);
            } else {
                // Send as plain text message
                $content = $this->replaceVariables($template->content, $recipientData);
                $this->sendWhatsAppTextMessage($config, $phoneNumber, $content);
            }

            Log::info("Drip campaign WhatsApp message sent successfully", [
                'template_id' => $template->id,
                'phone' => $phoneNumber,
                'recipient' => $recipientData['name'] ?? 'Unknown',
            ]);
        } catch (\Exception $e) {
            Log::error("Drip campaign WhatsApp message failed", [
                'template_id' => $template->id,
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Re-throw to be caught by caller
        }
    }

    /**
     * Get fresh WhatsApp config from database settings
     */
    protected function getWhatsAppConfig(): array
    {
        // Clear cache to ensure fresh data
        \Illuminate\Support\Facades\Cache::forget('setting_integration_whatsapp_meta');
        
        $whatsappMeta = Setting::getValue('integration_whatsapp_meta', []);
        
        if (!is_array($whatsappMeta)) {
            $whatsappMeta = [];
        }

        return [
            'enabled' => $whatsappMeta['enabled'] ?? false,
            'api_token' => $whatsappMeta['access_token'] ?? null,
            'phone_number_id' => $whatsappMeta['phone_number_id'] ?? null,
            'version' => $whatsappMeta['api_version'] ?? 'v19.0',
            'language' => $whatsappMeta['language'] ?? 'en',
        ];
    }


    /**
     * Send WhatsApp template message
     */
    protected function sendWhatsAppTemplateMessage(array $config, string $phoneNumber, $template, array $recipientData): void
    {
        // Extract template variables from content
        $content = $template->content ?? '';
        $variables = $this->extractTemplateVariables($content, $recipientData);
        
        // Build parameters array for template
        $parameters = [];
        foreach ($variables as $var) {
            $parameters[] = [
                'type' => 'text',
                'text' => $var,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $phoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $template->whatsapp_template_id,
                'language' => ['code' => $config['language'] ?? 'en'],
            ],
        ];

        // Add components with parameters if we have variables
        if (!empty($parameters)) {
            $payload['template']['components'] = [
                [
                    'type' => 'body',
                    'parameters' => $parameters,
                ],
            ];
        }

        $response = Http::withToken($config['api_token'])
            ->timeout(30)
            ->post("https://graph.facebook.com/{$config['version']}/{$config['phone_number_id']}/messages", $payload);

        if ($response->failed()) {
            $errorBody = $response->json();
            $errorMessage = $errorBody['error']['message'] ?? $response->body();
            throw new \Exception('WhatsApp template message failed: ' . $errorMessage);
        }
    }

    /**
     * Send WhatsApp plain text message
     */
    protected function sendWhatsAppTextMessage(array $config, string $phoneNumber, string $content): void
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $phoneNumber,
            'type' => 'text',
            'text' => [
                'body' => $content,
            ],
        ];

        $response = Http::withToken($config['api_token'])
            ->timeout(30)
            ->post("https://graph.facebook.com/{$config['version']}/{$config['phone_number_id']}/messages", $payload);

        if ($response->failed()) {
            $errorBody = $response->json();
            $errorMessage = $errorBody['error']['message'] ?? $response->body();
            throw new \Exception('WhatsApp text message failed: ' . $errorMessage);
        }
    }

    /**
     * Extract template variables from content and replace with recipient data
     */
    protected function extractTemplateVariables(string $content, array $recipientData): array
    {
        // Find all {{variable}} patterns
        preg_match_all('/\{\{(\w+)\}\}/', $content, $matches);
        
        $variables = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $varName) {
                $value = match(strtolower($varName)) {
                    'name' => $recipientData['name'] ?? '',
                    'email' => $recipientData['email'] ?? '',
                    'phone' => $recipientData['phone'] ?? '',
                    default => '',
                };
                $variables[] = $value;
            }
        }
        
        return $variables;
    }

    protected function replaceVariables(string $content, array $recipientData): string
    {
        $replacements = [
            '{{name}}' => $recipientData['name'],
            '{{email}}' => $recipientData['email'] ?? '',
            '{{phone}}' => $recipientData['phone'] ?? '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    protected function calculateNextSendAt(DripCampaign $campaign, ?DripCampaignStep $step, ?Carbon $base = null): Carbon
    {
        $baseTime = $base ? $base->copy() : now();

        if ($step) {
            $baseTime = $baseTime->addHours($step->delay_hours ?? 0);

            if ($step->wait_until_event) {
                $baseTime = $this->applyWaitEventDelay($step->wait_until_event, $baseTime);
            }
        }

        return $this->alignToSendWindow($campaign, $baseTime);
    }

    protected function applyWaitEventDelay(string $event, Carbon $time): Carbon
    {
        return match($event) {
            'customer_response' => $time->addHours(12),
            'payment_received' => $time->addHours(24),
            'business_hours' => $time->addHours(1),
            default => $time,
        };
    }

    protected function alignToSendWindow(DripCampaign $campaign, Carbon $time): Carbon
    {
        $timezone = $campaign->timezone ?? config('app.timezone');
        $start = $campaign->send_window_start;
        $end = $campaign->send_window_end;

        if (!$start || !$end) {
            return $time;
        }

        $localized = $time->copy()->setTimezone($timezone);
        $startTime = Carbon::parse($start, $timezone)->setDate($localized->year, $localized->month, $localized->day);
        $endTime = Carbon::parse($end, $timezone)->setDate($localized->year, $localized->month, $localized->day);

        if ($endTime->lessThanOrEqualTo($startTime)) {
            $endTime->addDay();
        }

        if ($localized->lessThan($startTime)) {
            $localized = $startTime;
        } elseif ($localized->greaterThan($endTime)) {
            $localized = $startTime->addDay();
        }

        return $localized->setTimezone(config('app.timezone'));
    }

    protected function isWithinSendWindow(DripCampaign $campaign): bool
    {
        $start = $campaign->send_window_start;
        $end = $campaign->send_window_end;

        if (!$start || !$end) {
            return true;
        }

        $timezone = $campaign->timezone ?? config('app.timezone');
        $now = now()->setTimezone($timezone);
        $startTime = Carbon::parse($start, $timezone)->setDate($now->year, $now->month, $now->day);
        $endTime = Carbon::parse($end, $timezone)->setDate($now->year, $now->month, $now->day);

        if ($endTime->lessThanOrEqualTo($startTime)) {
            $endTime->addDay();
        }

        return $now->betweenIncluded($startTime, $endTime);
    }

    protected function passesAudienceFilters(DripCampaign $campaign, string $recipientType, int $recipientId): bool
    {
        $filters = $campaign->audience_filters ?? [];

        if (empty($filters)) {
            return true;
        }

        foreach ($filters as $filter) {
            if (!$this->passesSingleFilter($filter, $recipientType, $recipientId)) {
                return false;
            }
        }

        return true;
    }

    protected function passesSingleFilter(string $filter, string $recipientType, int $recipientId): bool
    {
        return match($filter) {
            'existing_customers' => $recipientType === 'customer'
                ? User::where('id', $recipientId)->whereHas('orders')->exists()
                : false,
            'recent_enquiries' => $recipientType === 'enquiry'
                ? Enquiry::where('id', $recipientId)->where('created_at', '>=', now()->subDays(30))->exists()
                : false,
            'hot_leads' => $recipientType === 'lead'
                ? Lead::where('id', $recipientId)->where(function ($q) {
                    $q->where('lead_score', '>=', 70)
                        ->orWhereHas('stage', fn ($stage) => $stage->where('is_won', true));
                })->exists()
                : false,
            'abandoned_carts' => $recipientType === 'customer'
                ? User::where('id', $recipientId)->whereHas('cartSessions', function ($q) {
                    $q->where('is_abandoned', true);
                })->exists()
                : false,
            default => true,
        };
    }

    protected function passesStepConditions(DripCampaignStep $step, $model): bool
    {
        $conditions = $step->conditions;

        if (empty($conditions)) {
            return true;
        }

        foreach ($conditions as $key => $expected) {
            if (!$model) {
                return false;
            }

            switch ($key) {
                case 'lead_stage':
                    if (($model->stage->slug ?? null) !== $expected && ($model->stage->name ?? null) !== $expected) {
                        return false;
                    }
                    break;
                case 'min_score':
                    if (($model->lead_score ?? 0) < (int) $expected) {
                        return false;
                    }
                    break;
                case 'has_orders':
                    if (method_exists($model, 'orders')) {
                        $hasOrders = $model->orders()->exists();
                        if ((bool)$expected !== $hasOrders) {
                            return false;
                        }
                    }
                    break;
                default:
                    // Unknown condition, skip
                    break;
            }
        }

        return true;
    }

    protected function handleStepFailure(DripCampaignRecipient $recipient, DripCampaign $campaign, array $errors): void
    {
        $maxRetries = $campaign->max_retries ?? 3;
        $retryCount = $recipient->retry_count ?? 0;
        $message = implode('; ', $errors);

        $existingPayload = (array) ($recipient->last_step_payload ?? []);

        if ($retryCount + 1 >= $maxRetries) {
            $recipient->update([
                'status' => 'failed',
                'last_error' => $message,
                'retry_count' => $retryCount + 1,
                'last_step_payload' => array_merge($existingPayload, [
                    'last_error' => $message,
                ]),
            ]);

            return;
        }

        $recipient->update([
            'retry_count' => $retryCount + 1,
            'last_error' => $message,
            'next_send_at' => $this->alignToSendWindow($campaign, now()->addMinutes($this->retryDelayMinutes)),
            'last_step_payload' => array_merge($existingPayload, [
                'last_error' => $message,
            ]),
        ]);
    }

    public function processScheduledCampaigns(): void
    {
        $recipients = DripCampaignRecipient::where('status', 'in_progress')
            ->where('next_send_at', '<=', now())
            ->with(['campaign.steps.template'])
            ->get();

        foreach ($recipients as $recipient) {
            $this->processRecipient($recipient);
        }
    }

    public function triggerForEnquiry(Enquiry $enquiry): void
    {
        $campaigns = DripCampaign::where('trigger_type', 'new_enquiry')
            ->where('is_active', true)
            ->get();

        foreach ($campaigns as $campaign) {
            $this->startCampaignForRecipient($campaign, 'enquiry', $enquiry->id);
        }
    }

    public function triggerForLead(Lead $lead): void
    {
        $campaigns = DripCampaign::where('trigger_type', 'new_lead')
            ->where('is_active', true)
            ->get();

        foreach ($campaigns as $campaign) {
            $this->startCampaignForRecipient($campaign, 'lead', $lead->id);
        }
    }
}

