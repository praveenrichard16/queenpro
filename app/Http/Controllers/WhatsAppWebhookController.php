<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Handle webhook verification (GET request from Meta)
     * Meta sends a GET request with hub.mode, hub.verify_token, and hub.challenge
     */
    public function verify(Request $request): Response
    {
        $mode = $request->query('hub_mode') ?? $request->query('hub.mode');
        $token = $request->query('hub_verify_token') ?? $request->query('hub.verify_token');
        $challenge = $request->query('hub_challenge') ?? $request->query('hub.challenge');

        // Get verify token from settings
        $whatsappMeta = Setting::getValue('integration_whatsapp_meta', []);
        $verifyToken = $whatsappMeta['webhook_verify_token'] ?? null;

        Log::info('WhatsApp webhook verification request', [
            'mode' => $mode,
            'token_received' => $token ? '***' : null,
            'token_expected' => $verifyToken ? '***' : null,
            'challenge' => $challenge ? '***' : null,
        ]);

        // Verify the mode and token
        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('WhatsApp webhook verified successfully');
            // Return the challenge to complete verification
            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }

        Log::warning('WhatsApp webhook verification failed', [
            'mode_match' => $mode === 'subscribe',
            'token_match' => $token === $verifyToken,
        ]);

        // Verification failed
        return response('Verification failed', 403);
    }

    /**
     * Handle webhook events (POST request from Meta) and verification (GET request)
     */
    public function handle(Request $request): Response
    {
        // Handle GET request for verification
        if ($request->isMethod('GET')) {
            return $this->verify($request);
        }

        // Handle POST request for events
        try {
            $payload = $request->all();

            Log::info('WhatsApp webhook event received', [
                'object' => $payload['object'] ?? null,
                'entry_count' => count($payload['entry'] ?? []),
            ]);

            // Verify this is a WhatsApp webhook
            if (($payload['object'] ?? null) !== 'whatsapp_business_account') {
                Log::warning('Invalid webhook object type', [
                    'object' => $payload['object'] ?? null,
                ]);
                return response('Invalid webhook object', 400);
            }

            // Process each entry
            foreach ($payload['entry'] ?? [] as $entry) {
                $this->processEntry($entry);
            }

            // Always return 200 to acknowledge receipt
            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('WhatsApp webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Still return 200 to prevent Meta from retrying
            return response('OK', 200);
        }
    }

    /**
     * Process a webhook entry
     */
    protected function processEntry(array $entry): void
    {
        $businessAccountId = $entry['id'] ?? null;
        $changes = $entry['changes'] ?? [];

        foreach ($changes as $change) {
            $value = $change['value'] ?? [];
            $field = $change['field'] ?? null;

            Log::info('Processing WhatsApp webhook change', [
                'business_account_id' => $businessAccountId,
                'field' => $field,
            ]);

            switch ($field) {
                case 'messages':
                    $this->handleMessages($value);
                    break;
                case 'message_template_status_update':
                    $this->handleTemplateStatusUpdate($value);
                    break;
                case 'message_template_quality_update':
                    $this->handleTemplateQualityUpdate($value);
                    break;
                default:
                    Log::info('Unhandled webhook field', [
                        'field' => $field,
                    ]);
            }
        }
    }

    /**
     * Handle incoming messages
     */
    protected function handleMessages(array $value): void
    {
        $messages = $value['messages'] ?? [];
        $statuses = $value['statuses'] ?? [];

        // Process incoming messages
        foreach ($messages as $message) {
            Log::info('WhatsApp message received', [
                'message_id' => $message['id'] ?? null,
                'from' => $message['from'] ?? null,
                'type' => $message['type'] ?? null,
            ]);

            // TODO: Handle incoming messages (e.g., customer inquiries, order updates)
            // You can add your business logic here
        }

        // Process message statuses (delivered, read, etc.)
        foreach ($statuses as $status) {
            Log::info('WhatsApp message status update', [
                'message_id' => $status['id'] ?? null,
                'status' => $status['status'] ?? null,
                'recipient_id' => $status['recipient_id'] ?? null,
            ]);

            // TODO: Handle status updates (e.g., mark messages as delivered/read)
            // You can add your business logic here
        }
    }

    /**
     * Handle template status updates
     */
    protected function handleTemplateStatusUpdate(array $value): void
    {
        $event = $value['event'] ?? null;
        $messageTemplateId = $value['message_template_id'] ?? null;
        $messageTemplateName = $value['message_template_name'] ?? null;
        $messageTemplateLanguage = $value['message_template_language'] ?? null;
        $reason = $value['reason'] ?? null;

        Log::info('WhatsApp template status update', [
            'event' => $event,
            'template_id' => $messageTemplateId,
            'template_name' => $messageTemplateName,
            'language' => $messageTemplateLanguage,
            'reason' => $reason,
        ]);

        // TODO: Update template status in database
        // You can sync the template status here
    }

    /**
     * Handle template quality updates
     */
    protected function handleTemplateQualityUpdate(array $value): void
    {
        $messageTemplateId = $value['message_template_id'] ?? null;
        $messageTemplateName = $value['message_template_name'] ?? null;
        $messageTemplateLanguage = $value['message_template_language'] ?? null;
        $newQualityRating = $value['new_quality_rating'] ?? null;
        $oldQualityRating = $value['old_quality_rating'] ?? null;

        Log::info('WhatsApp template quality update', [
            'template_id' => $messageTemplateId,
            'template_name' => $messageTemplateName,
            'language' => $messageTemplateLanguage,
            'new_rating' => $newQualityRating,
            'old_rating' => $oldQualityRating,
        ]);

        // TODO: Update template quality rating in database
    }
}

