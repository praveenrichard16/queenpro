<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function sendOrderCreated(Order $order): void
    {
        static::sendTemplateMessage($order, 'order_created');
    }

    public static function sendOrderStatusUpdated(Order $order, string $oldStatus, string $newStatus): void
    {
        static::sendTemplateMessage($order, 'order_status_updated', [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);
    }

    protected static function sendTemplateMessage(Order $order, string $eventKey, array $extra = []): void
    {
        $config = config('services.whatsapp');

        if (empty($config['enabled']) || empty($config['api_token']) || empty($config['phone_number_id'])) {
            // WhatsApp is not configured; silently skip
            return;
        }

        $templateName = $config['templates'][$eventKey] ?? null;
        if (!$templateName) {
            return;
        }

        try {
            // Prepare parameters based on event type
            $parameters = [];
            if ($eventKey === 'order_created') {
                $parameters = [
                    ['type' => 'text', 'text' => $order->customer_name ?? 'Customer'],
                    ['type' => 'text', 'text' => $order->order_number ?? '#' . $order->id],
                    ['type' => 'text', 'text' => $order->formatted_total],
                ];
            } elseif ($eventKey === 'order_status_updated') {
                $parameters = [
                    ['type' => 'text', 'text' => $order->customer_name ?? 'Customer'],
                    ['type' => 'text', 'text' => $order->order_number ?? '#' . $order->id],
                    ['type' => 'text', 'text' => ucfirst($extra['old_status'] ?? 'Unknown')],
                    ['type' => 'text', 'text' => ucfirst($extra['new_status'] ?? 'Unknown')],
                ];
            }

            // Format phone number for WhatsApp API
            $phoneNumber = PhoneNumberService::formatForWhatsApp(
                PhoneNumberService::normalize($order->customer_phone, $order->customer_phone_country_code ?? null)['phone'] ?? $order->customer_phone
            );

            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => ['code' => $config['language'] ?? 'en'],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => $parameters,
                        ],
                    ],
                ],
            ];

            Http::withToken($config['api_token'])
                ->post("https://graph.facebook.com/{$config['version']}/{$config['phone_number_id']}/messages", $payload)
                ->throw();
        } catch (\Throwable $e) {
            Log::warning('WhatsApp order notification failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'event' => $eventKey,
            ]);
        }
    }
}


