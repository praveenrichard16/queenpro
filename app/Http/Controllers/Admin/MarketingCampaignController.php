<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingCampaign;
use App\Models\MarketingTemplate;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MarketingCampaignController extends Controller
{
    public function index(): View
    {
        if (!Schema::hasTable('marketing_campaigns')) {
            $campaigns = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1);
            return view('admin.marketing.campaigns.index', compact('campaigns'));
        }

        $campaigns = MarketingCampaign::with('template')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.marketing.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $templates = Schema::hasTable('marketing_templates')
            ? MarketingTemplate::where('is_active', true)->orderBy('name')->get()
            : collect([]);
        return view('admin.marketing.campaigns.form', [
            'campaign' => new MarketingCampaign(),
            'templates' => $templates,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'template_id' => ['required', 'exists:marketing_templates,id'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,scheduled,sending,completed,cancelled'],
            'recipient_filters' => ['nullable', 'array'],
            'recipient_list' => ['nullable', 'array'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        // Calculate recipients based on filters or list
        $recipients = $this->calculateRecipients($data['recipient_filters'] ?? [], $data['recipient_list'] ?? []);
        $data['total_recipients'] = count($recipients);
        $data['recipient_list'] = $recipients;

        MarketingCampaign::create($data);

        return redirect()->route('admin.marketing.campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    public function edit(MarketingCampaign $campaign): View
    {
        $templates = Schema::hasTable('marketing_templates')
            ? MarketingTemplate::where('is_active', true)->orderBy('name')->get()
            : collect([]);
        return view('admin.marketing.campaigns.form', compact('campaign', 'templates'));
    }

    public function update(Request $request, MarketingCampaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'template_id' => ['required', 'exists:marketing_templates,id'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,scheduled,sending,completed,cancelled'],
            'recipient_filters' => ['nullable', 'array'],
            'recipient_list' => ['nullable', 'array'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        $recipients = $this->calculateRecipients($data['recipient_filters'] ?? [], $data['recipient_list'] ?? []);
        $data['total_recipients'] = count($recipients);
        $data['recipient_list'] = $recipients;

        $campaign->update($data);

        return redirect()->route('admin.marketing.campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    public function show(MarketingCampaign $campaign): View
    {
        $campaign->load('template');
        return view('admin.marketing.campaigns.show', compact('campaign'));
    }

    public function send(MarketingCampaign $campaign): RedirectResponse
    {
        if ($campaign->status !== 'draft' && $campaign->status !== 'scheduled') {
            return back()->with('error', 'Campaign cannot be sent in its current status.');
        }

        $campaign->update([
            'status' => 'sending',
            'sent_at' => now(),
        ]);

        // Here you would actually send the messages
        // For now, just update the status
        $campaign->update([
            'status' => 'completed',
            'sent_count' => $campaign->total_recipients,
        ]);

        return back()->with('success', 'Campaign sent successfully.');
    }

    private function calculateRecipients(array $filters, array $list): array
    {
        if (!empty($list)) {
            return $list;
        }

        $query = User::where('is_admin', false);

        if (isset($filters['has_orders']) && $filters['has_orders']) {
            $query->has('orders');
        }

        if (isset($filters['min_orders']) && $filters['min_orders'] > 0) {
            $query->has('orders', '>=', $filters['min_orders']);
        }

        if (isset($filters['cart_abandoners']) && $filters['cart_abandoners']) {
            $query->whereHas('cartSessions', function($q) {
                $q->where('is_abandoned', true);
            });
        }

        return $query->pluck('id')->toArray();
    }
}

