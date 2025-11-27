<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebhookEndpoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebhookController extends Controller
{
    public function index(): View
    {
        $endpoints = WebhookEndpoint::orderBy('created_at', 'desc')->paginate(20);
        
        // Statistics
        $totalWebhooks = WebhookEndpoint::count();
        $recentWebhooks = WebhookEndpoint::where('created_at', '>=', now()->subDays(1))->count();
        $activeWebhooks = WebhookEndpoint::active()->count();
        
        // Get webhook log statistics
        $successful = \App\Models\WebhookLog::successful()->count();
        $failed = \App\Models\WebhookLog::failed()->count();
        
        return view('admin.api.webhooks.index', [
            'endpoints' => $endpoints,
            'totalWebhooks' => $totalWebhooks,
            'recentWebhooks' => $recentWebhooks,
            'activeWebhooks' => $activeWebhooks,
            'successful' => $successful,
            'failed' => $failed,
        ]);
    }

    public function create(): View
    {
        return view('admin.api.webhooks.form', [
            'endpoint' => new WebhookEndpoint(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'url' => ['required', 'url', 'max:500'],
            'secret' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'events' => ['nullable', 'array'],
            'events.*' => ['string'],
            'is_active' => ['sometimes', 'boolean'],
            'timeout' => ['nullable', 'integer', 'min:1', 'max:300'],
            'max_attempts' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['timeout'] = $data['timeout'] ?? 30;
        $data['max_attempts'] = $data['max_attempts'] ?? 3;

        WebhookEndpoint::create($data);

        return redirect()
            ->route('admin.api.webhooks.index')
            ->with('success', 'Webhook endpoint created successfully.');
    }

    public function edit(WebhookEndpoint $webhook_endpoint): View
    {
        return view('admin.api.webhooks.form', [
            'endpoint' => $webhook_endpoint,
        ]);
    }

    public function update(Request $request, WebhookEndpoint $webhook_endpoint): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'url' => ['required', 'url', 'max:500'],
            'secret' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'events' => ['nullable', 'array'],
            'events.*' => ['string'],
            'is_active' => ['sometimes', 'boolean'],
            'timeout' => ['nullable', 'integer', 'min:1', 'max:300'],
            'max_attempts' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['timeout'] = $data['timeout'] ?? 30;
        $data['max_attempts'] = $data['max_attempts'] ?? 3;

        $webhook_endpoint->update($data);

        return redirect()
            ->route('admin.api.webhooks.index')
            ->with('success', 'Webhook endpoint updated successfully.');
    }

    public function destroy(WebhookEndpoint $webhook_endpoint): RedirectResponse
    {
        $webhook_endpoint->delete();

        return redirect()
            ->route('admin.api.webhooks.index')
            ->with('success', 'Webhook endpoint deleted successfully.');
    }
}

