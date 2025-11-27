<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DripCampaign;
use App\Models\DripCampaignRecipient;
use App\Models\DripCampaignStep;
use App\Models\Enquiry;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\MarketingTemplate;
use App\Models\User;
use App\Services\DripCampaignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DripCampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = DripCampaign::withCount(['steps', 'recipients'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.marketing.drip-campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $templates = MarketingTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.marketing.drip-campaigns.form', [
            'campaign' => new DripCampaign(),
            'templates' => $templates,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'trigger_type' => ['required', 'in:new_enquiry,new_lead,manual'],
            'channel' => ['required', 'in:email,whatsapp,both'],
            'is_active' => ['sometimes', 'boolean'],
            'timezone' => ['nullable', 'timezone'],
            'send_window_start' => ['nullable', 'date_format:H:i'],
            'send_window_end' => ['nullable', 'date_format:H:i'],
            'max_retries' => ['nullable', 'integer', 'min:0', 'max:10'],
            'audience_filters' => ['nullable', 'array'],
            'audience_filters.*' => ['string'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*.step_number' => ['required', 'integer', 'min:1'],
            'steps.*.delay_hours' => ['required', 'integer', 'min:0'],
            'steps.*.template_id' => ['required', 'exists:marketing_templates,id'],
            'steps.*.channel' => ['required', 'in:email,whatsapp'],
            'steps.*.is_active' => ['sometimes', 'boolean'],
            'steps.*.conditions' => ['nullable', 'string'],
            'steps.*.wait_until_event' => ['nullable', 'string', 'max:100'],
        ]);

        $campaign = DripCampaign::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'trigger_type' => $data['trigger_type'],
            'channel' => $data['channel'],
            'is_active' => $request->boolean('is_active', true),
            'timezone' => $data['timezone'] ?? config('app.timezone'),
            'send_window_start' => $data['send_window_start'] ?? null,
            'send_window_end' => $data['send_window_end'] ?? null,
            'max_retries' => $data['max_retries'] ?? 3,
            'audience_filters' => $data['audience_filters'] ?? [],
        ]);

        foreach ($data['steps'] as $stepData) {
            DripCampaignStep::create([
                'drip_campaign_id' => $campaign->id,
                'step_number' => $stepData['step_number'],
                'delay_hours' => $stepData['delay_hours'],
                'template_id' => $stepData['template_id'],
                'channel' => $stepData['channel'],
                'is_active' => $stepData['is_active'] ?? true,
                'conditions' => $this->normalizeConditions($stepData['conditions'] ?? null),
                'wait_until_event' => $stepData['wait_until_event'] ?? null,
            ]);
        }

        return redirect()->route('admin.marketing.drip-campaigns.index')
            ->with('success', 'Drip campaign created successfully.');
    }

    public function show(DripCampaign $dripCampaign): View
    {
        $dripCampaign->load(['steps.template', 'recipients']);
        
        return view('admin.marketing.drip-campaigns.show', compact('dripCampaign'));
    }

    public function edit(DripCampaign $dripCampaign): View
    {
        $dripCampaign->load('steps');
        $templates = MarketingTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.marketing.drip-campaigns.form', [
            'campaign' => $dripCampaign,
            'templates' => $templates,
        ]);
    }

    public function update(Request $request, DripCampaign $dripCampaign): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'trigger_type' => ['required', 'in:new_enquiry,new_lead,manual'],
            'channel' => ['required', 'in:email,whatsapp,both'],
            'is_active' => ['sometimes', 'boolean'],
            'timezone' => ['nullable', 'timezone'],
            'send_window_start' => ['nullable', 'date_format:H:i'],
            'send_window_end' => ['nullable', 'date_format:H:i'],
            'max_retries' => ['nullable', 'integer', 'min:0', 'max:10'],
            'audience_filters' => ['nullable', 'array'],
            'audience_filters.*' => ['string'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*.step_number' => ['required', 'integer', 'min:1'],
            'steps.*.delay_hours' => ['required', 'integer', 'min:0'],
            'steps.*.template_id' => ['required', 'exists:marketing_templates,id'],
            'steps.*.channel' => ['required', 'in:email,whatsapp'],
            'steps.*.is_active' => ['sometimes', 'boolean'],
            'steps.*.conditions' => ['nullable', 'string'],
            'steps.*.wait_until_event' => ['nullable', 'string', 'max:100'],
        ]);

        $dripCampaign->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'trigger_type' => $data['trigger_type'],
            'channel' => $data['channel'],
            'is_active' => $request->boolean('is_active', true),
            'timezone' => $data['timezone'] ?? config('app.timezone'),
            'send_window_start' => $data['send_window_start'] ?? null,
            'send_window_end' => $data['send_window_end'] ?? null,
            'max_retries' => $data['max_retries'] ?? 3,
            'audience_filters' => $data['audience_filters'] ?? [],
        ]);

        // Delete existing steps and recreate
        $dripCampaign->steps()->delete();

        foreach ($data['steps'] as $stepData) {
            DripCampaignStep::create([
                'drip_campaign_id' => $dripCampaign->id,
                'step_number' => $stepData['step_number'],
                'delay_hours' => $stepData['delay_hours'],
                'template_id' => $stepData['template_id'],
                'channel' => $stepData['channel'],
                'is_active' => $stepData['is_active'] ?? true,
                'conditions' => $this->normalizeConditions($stepData['conditions'] ?? null),
                'wait_until_event' => $stepData['wait_until_event'] ?? null,
            ]);
        }

        return redirect()->route('admin.marketing.drip-campaigns.index')
            ->with('success', 'Drip campaign updated successfully.');
    }

    public function destroy(DripCampaign $dripCampaign): RedirectResponse
    {
        $dripCampaign->delete();

        return redirect()->route('admin.marketing.drip-campaigns.index')
            ->with('success', 'Drip campaign deleted successfully.');
    }

    public function trigger(Request $request): View
    {
        $campaigns = DripCampaign::where('trigger_type', 'manual')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedCampaign = null;
        if ($request->filled('campaign_id')) {
            $selectedCampaign = DripCampaign::with(['steps.template'])->find($request->get('campaign_id'));
        }

        $recipientType = $request->get('recipient_type', 'customer');
        $customers = collect();
        $enquiries = collect();
        $leads = collect();
        $leadStages = LeadStage::orderBy('name')->get();

        if ($recipientType === 'customer') {
            $query = User::where('is_admin', false)->where('is_staff', false);

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->boolean('has_orders')) {
                $query->whereHas('orders');
            }

            if ($request->boolean('has_abandoned_cart')) {
                $query->whereHas('cartSessions', function($q) {
                    $q->where('is_abandoned', true);
                });
            }

            $customers = $query->paginate(20)->withQueryString();
        } elseif ($recipientType === 'enquiry') {
            $query = Enquiry::with('product');

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            }

            if ($request->boolean('recent_only')) {
                $query->where('created_at', '>=', now()->subDays(30));
            }

            $enquiries = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        } elseif ($recipientType === 'lead') {
            $query = Lead::with('stage');

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            if ($request->filled('lead_stage_id')) {
                $query->where('lead_stage_id', $request->get('lead_stage_id'));
            }

            if ($request->filled('min_score')) {
                $query->where('lead_score', '>=', (int) $request->get('min_score'));
            }

            if ($request->boolean('only_hot')) {
                $query->where(function($q) {
                    $q->where('lead_score', '>=', 70)
                      ->orWhereHas('stage', fn($stage) => $stage->where('is_won', true));
                });
            }

            $leads = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        }

        return view('admin.marketing.drip-campaigns.trigger', compact(
            'campaigns',
            'customers',
            'enquiries',
            'leads',
            'recipientType',
            'selectedCampaign',
            'leadStages'
        ));
    }

    public function triggerCampaign(Request $request, DripCampaignService $service): RedirectResponse
    {
        $data = $request->validate([
            'campaign_id' => ['required', 'exists:drip_campaigns,id'],
            'recipient_type' => ['required', 'in:customer,enquiry,lead'],
            'recipient_ids' => ['required', 'array', 'min:1'],
            'recipient_ids.*' => ['required', 'integer'],
        ]);

        $campaign = DripCampaign::findOrFail($data['campaign_id']);
        $recipientType = $data['recipient_type'];
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($data['recipient_ids'] as $recipientId) {
            try {
                // Validate recipient exists
                $exists = match($recipientType) {
                    'customer' => User::where('id', $recipientId)->where('is_admin', false)->where('is_staff', false)->exists(),
                    'enquiry' => Enquiry::where('id', $recipientId)->exists(),
                    'lead' => Lead::where('id', $recipientId)->exists(),
                    default => false,
                };

                if (!$exists) {
                    $errorCount++;
                    $errors[] = "Recipient #{$recipientId} not found";
                    continue;
                }

                $service->startCampaignForRecipient($campaign, $recipientType, $recipientId);
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Recipient #{$recipientId}: " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error('Failed to trigger campaign for recipient', [
                    'campaign_id' => $campaign->id,
                    'recipient_type' => $recipientType,
                    'recipient_id' => $recipientId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $message = "Drip campaign triggered successfully for {$successCount} recipient(s).";
        if ($errorCount > 0) {
            $message .= " {$errorCount} recipient(s) failed.";
            if (count($errors) <= 5) {
                $message .= " Errors: " . implode('; ', $errors);
            }
        }

        return redirect()->route('admin.marketing.drip-campaigns.trigger', [
            'campaign_id' => $campaign->id,
            'recipient_type' => $recipientType,
        ])->with($errorCount > 0 && $successCount == 0 ? 'error' : 'success', $message);
    }

    protected function normalizeConditions($raw): ?array
    {
        if (is_array($raw)) {
            return array_filter($raw, fn ($value) => $value !== null && $value !== '');
        }

        if (is_string($raw)) {
            $raw = trim($raw);

            if ($raw === '') {
                return null;
            }

            $decoded = json_decode($raw, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            // fall back to simple key:value parsing (one per line)
            $result = [];

            foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
                $line = trim($line);

                if ($line === '' || !str_contains($line, ':')) {
                    continue;
                }

                [$key, $value] = array_map('trim', explode(':', $line, 2));
                $result[$key] = $value;
            }

            return empty($result) ? ['expression' => $raw] : $result;
        }

        return null;
    }
}
