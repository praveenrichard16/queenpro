<?php

namespace App\Services\Integration;

use App\Services\Integration\Exceptions\IntegrationException;
use Illuminate\Support\Facades\Http;

class FcmService
{
    /**
     * @param  array<string, mixed>  $credentials
     */
    public function sendTestNotification(array $credentials, string $title, string $body): void
    {
        $serverKey = $credentials['server_key'] ?? null;

        if (!$serverKey) {
            throw new IntegrationException('A Firebase server key is required to send notifications.');
        }

        $payload = [
            'to' => '/topics/wowdash_admin_test',
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => [
                'source' => 'wowdash-admin',
                'timestamp' => now()->toIso8601String(),
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', $payload);

            if ($response->failed()) {
                throw new IntegrationException('Firebase Cloud Messaging returned an error: ' . $response->body());
            }
        } catch (\Throwable $throwable) {
            throw IntegrationException::fromThrowable('Failed to reach Firebase Cloud Messaging.', $throwable);
        }
    }
}

