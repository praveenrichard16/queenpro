<?php

namespace App\Services\Integration;

use App\Services\Integration\Exceptions\IntegrationException;
use Illuminate\Support\Facades\Http;

class MetaWhatsAppService
{
    /**
     * @param  array<string, mixed>  $credentials
     */
    public function verifyCredentials(array $credentials): void
    {
        $phoneNumberId = $credentials['phone_number_id'] ?? null;
        $token = $credentials['access_token'] ?? null;
        $apiVersion = $credentials['api_version'] ?? 'v19.0';

        if (!$phoneNumberId || !$token) {
            throw new IntegrationException('Meta WhatsApp credentials require phone number ID and access token.');
        }

        try {
            $response = Http::withToken($token)
                ->get("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}", [
                    'fields' => 'id,display_phone_number',
                ]);

            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();
                throw new IntegrationException('Meta WhatsApp credentials could not be verified: ' . $errorMessage);
            }
        } catch (IntegrationException $e) {
            throw $e;
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Failed to reach Meta Graph API: ' . $throwable->getMessage(), $throwable);
        }
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function sendTestMessage(array $credentials, string $to, string $body): void
    {
        $phoneNumberId = $credentials['phone_number_id'] ?? null;
        $token = $credentials['access_token'] ?? null;
        $apiVersion = $credentials['api_version'] ?? 'v19.0';

        if (!$phoneNumberId || !$token) {
            throw new IntegrationException('Meta WhatsApp credentials require phone number ID and access token.');
        }

        // Clean phone number (remove +, spaces, dashes)
        $to = preg_replace('/[^0-9]/', '', $to);

        try {
            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'body' => $body,
                    ],
                ]);

            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? $response->body();
                throw new IntegrationException('Meta WhatsApp could not send the test message: ' . $errorMessage);
            }
        } catch (IntegrationException $e) {
            throw $e;
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Failed to send message via Meta WhatsApp: ' . $throwable->getMessage(), $throwable);
        }
    }
}

