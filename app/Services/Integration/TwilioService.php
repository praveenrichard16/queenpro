<?php

namespace App\Services\Integration;

use App\Services\Integration\Exceptions\IntegrationException;
use Illuminate\Support\Facades\Http;

class TwilioService
{
    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyCredentials(array $credentials): void
    {
        $accountSid = $credentials['account_sid'] ?? null;
        $authToken = $credentials['auth_token'] ?? null;

        if (!$accountSid || !$authToken) {
            throw new IntegrationException('Twilio Account SID and Auth Token are required.');
        }

        try {
            $response = Http::withBasicAuth($accountSid, $authToken)
                ->get("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}.json");

            if ($response->failed()) {
                throw new IntegrationException('Twilio credentials could not be verified: ' . $response->body());
            }
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Failed to reach Twilio API.', $throwable);
        }
    }

    /**
     * @param  array<string, mixed>  $credentials
     * @param  string  $channel  sms|whatsapp
     */
    public function sendMessage(array $credentials, string $channel, string $to, string $body): void
    {
        $accountSid = $credentials['account_sid'] ?? null;
        $authToken = $credentials['auth_token'] ?? null;
        $from = $credentials['from_number'] ?? null;

        if ($channel === 'whatsapp') {
            $from = $from ?? $credentials['sandbox_number'] ?? null;
            $from = $this->formatWhatsAppNumber($from);
            $to = $this->formatWhatsAppNumber($to);
        }

        if (!$accountSid || !$authToken || !$from) {
            throw new IntegrationException('Twilio credentials are incomplete. Please provide Account SID, Auth Token, and From number.');
        }

        try {
            $payload = [
                'To' => $to,
                'Body' => $body,
            ];

            if (!empty($credentials['messaging_service_sid'])) {
                $payload['MessagingServiceSid'] = $credentials['messaging_service_sid'];
            } else {
                $payload['From'] = $from;
            }

            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", $payload);

            if ($response->failed()) {
                throw new IntegrationException('Twilio could not send the message: ' . $response->body());
            }
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Failed to send message via Twilio.', $throwable);
        }
    }

    protected function formatWhatsAppNumber(?string $value): string
    {
        if (!$value) {
            return '';
        }

        $value = trim($value);

        if (!str_starts_with($value, 'whatsapp:')) {
            $value = 'whatsapp:' . $value;
        }

        return $value;
    }
}

