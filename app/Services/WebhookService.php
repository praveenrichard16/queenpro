<?php

namespace App\Services;

use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebhookService
{
    /**
     * Send webhook for a given event
     */
    public function send(string $eventType, array $payload, ?string $source = null): void
    {
        // Get all active endpoints that should handle this event
        $query = WebhookEndpoint::active();
        
        // Filter by events
        $query->where(function ($q) use ($eventType) {
            $q->whereNull('events')
                ->orWhereJsonContains('events', $eventType);
        });
        
        // Filter by source if provided
        if ($source) {
            $query->where(function ($q) use ($source) {
                $q->whereNull('source')
                    ->orWhere('source', $source);
            });
        }
        
        $endpoints = $query->get();
        
        foreach ($endpoints as $endpoint) {
            if ($endpoint->shouldHandleEvent($eventType)) {
                $this->sendToEndpoint($endpoint, $eventType, $payload, $source);
            }
        }
    }
    
    /**
     * Send webhook to a specific endpoint
     */
    protected function sendToEndpoint(WebhookEndpoint $endpoint, string $eventType, array $payload, ?string $source = null): void
    {
        $attempt = 1;
        $maxAttempts = $endpoint->max_attempts ?? 3;
        
        while ($attempt <= $maxAttempts) {
            $log = WebhookLog::create([
                'webhook_endpoint_id' => $endpoint->id,
                'event_type' => $eventType,
                'source' => $source,
                'status' => 'pending',
                'attempt' => $attempt,
                'payload' => json_encode($payload),
            ]);
            
            try {
                $response = Http::timeout($endpoint->timeout ?? 30)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'X-Webhook-Event' => $eventType,
                        'X-Webhook-Source' => $source ?? 'system',
                        'X-Webhook-Signature' => $this->generateSignature($payload, $endpoint->secret),
                    ])
                    ->post($endpoint->url, $payload);
                
                $log->update([
                    'status' => $response->successful() ? 'successful' : 'failed',
                    'response_code' => $response->status(),
                    'response' => $response->body(),
                    'sent_at' => now(),
                ]);
                
                if ($response->successful()) {
                    break; // Success, no need to retry
                }
            } catch (\Exception $e) {
                $log->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'response_code' => 0,
                    'sent_at' => now(),
                ]);
            }
            
            $attempt++;
            
            // Wait before retry (exponential backoff)
            if ($attempt <= $maxAttempts) {
                sleep(min($attempt * 2, 10));
            }
        }
    }
    
    /**
     * Generate signature for webhook payload
     */
    protected function generateSignature(array $payload, ?string $secret): string
    {
        if (!$secret) {
            return '';
        }
        
        $payloadString = json_encode($payload);
        return hash_hmac('sha256', $payloadString, $secret);
    }
}

