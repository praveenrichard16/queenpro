<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiUsageLog;
use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class ApiUsageStatisticsController extends Controller
{
    public function index(Request $request): View
    {
        // Token Statistics
        $totalTokens = PersonalAccessToken::where('tokenable_type', 'App\Models\User')->count();
        
        // Count tokens that have been used at least once (active tokens)
        $tokenIdsWithUsage = ApiUsageLog::distinct('token_id')
            ->whereNotNull('token_id')
            ->pluck('token_id')
            ->toArray();
        $activeTokens = count($tokenIdsWithUsage);
        
        // Recent usage (last 7 days)
        $recentUsage = ApiUsageLog::recent(7)->count();
        
        // Webhook Statistics
        $totalWebhooks = WebhookEndpoint::count();
        $webhookSuccessful = WebhookLog::recent(7)->successful()->count();
        $webhookFailed = WebhookLog::recent(7)->failed()->count();
        
        // Calculate success rate
        $totalWebhookAttempts = $webhookSuccessful + $webhookFailed;
        $webhookSuccessRate = $totalWebhookAttempts > 0 
            ? round(($webhookSuccessful / $totalWebhookAttempts) * 100, 1) 
            : 0;
        
        // Token Usage Table
        $tokens = PersonalAccessToken::where('tokenable_type', 'App\Models\User')
            ->with('tokenable')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get usage info for each token
        foreach ($tokens as $token) {
            $lastUsage = ApiUsageLog::where('token_id', $token->id)
                ->orderBy('created_at', 'desc')
                ->first();
            $token->last_used_at = $lastUsage?->created_at;
            $token->has_usage = !is_null($lastUsage);
        }
        
        return view('admin.api.usage-statistics', [
            'totalTokens' => $totalTokens,
            'activeTokens' => $activeTokens,
            'recentUsage' => $recentUsage,
            'totalWebhooks' => $totalWebhooks,
            'webhookSuccessRate' => $webhookSuccessRate,
            'tokens' => $tokens,
        ]);
    }
}

