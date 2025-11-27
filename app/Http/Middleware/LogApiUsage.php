<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiUsageLog;
use Laravel\Sanctum\PersonalAccessToken;

class LogApiUsage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        // Only log API requests (routes starting with /api)
        if (!$request->is('api/*')) {
            return $response;
        }
        
        // Skip health check endpoint
        if ($request->is('api/health')) {
            return $response;
        }
        
        // Get the authenticated user and token
        $user = $request->user();
        $token = null;
        $tokenId = null;
        
        if ($user && method_exists($user, 'currentAccessToken')) {
            $token = $user->currentAccessToken();
            $tokenId = $token?->id;
        }
        
        // Calculate response time
        $responseTime = round((microtime(true) - $startTime) * 1000);
        
        // Get request body (limit size to prevent huge logs)
        $requestBody = $request->getContent();
        if (strlen($requestBody) > 50000) {
            $requestBody = substr($requestBody, 0, 50000) . '... [truncated]';
        }
        
        // Get response body (limit size)
        $responseBody = $response->getContent();
        if (strlen($responseBody) > 50000) {
            $responseBody = substr($responseBody, 0, 50000) . '... [truncated]';
        }
        
        // Log the API usage asynchronously to avoid blocking the response
        try {
            ApiUsageLog::create([
                'token_id' => $tokenId,
                'user_id' => $user?->id,
                'endpoint' => $request->path(),
                'method' => $request->method(),
                'status_code' => $response->getStatusCode(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'response_time' => $responseTime,
                'request_headers' => $this->sanitizeHeaders($request->headers->all()),
                'request_body' => $requestBody,
                'response_body' => $responseBody,
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to log API usage: ' . $e->getMessage());
        }
        
        return $response;
    }
    
    /**
     * Sanitize headers to remove sensitive information
     */
    private function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = ['authorization', 'cookie', 'x-csrf-token', 'x-xsrf-token'];
        $sanitized = [];
        
        foreach ($headers as $key => $value) {
            $lowerKey = strtolower($key);
            if (in_array($lowerKey, $sensitiveHeaders)) {
                $sanitized[$key] = ['[REDACTED]'];
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}

