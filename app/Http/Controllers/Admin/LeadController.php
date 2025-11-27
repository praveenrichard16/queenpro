<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadFollowup;
use App\Models\LeadSource;
use App\Models\LeadStage;
use App\Models\User;
use App\Services\DripCampaignService;
use App\Services\LeadAnalyticsService;
use App\Services\LeadImportExportService;
use App\Services\LeadScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'add');
        $leadSources = LeadSource::where('is_active', true)->orderBy('name')->get();
        $leadStages = LeadStage::orderBy('sort_order')->get();
        $users = User::where('is_staff', true)->where('is_admin', false)->orderBy('name')->get();

        // Initialize empty paginators for all tabs
        $leads = new LengthAwarePaginator([], 0, 20, 1);
        $assignLeads = new LengthAwarePaginator([], 0, 20, 1);
        $todayFollowups = new LengthAwarePaginator([], 0, 20, 1, ['pageName' => 'today_page']);
        $upcomingFollowups = new LengthAwarePaginator([], 0, 20, 1, ['pageName' => 'upcoming_page']);
        $trashedLeads = new LengthAwarePaginator([], 0, 20, 1);
        $analytics = [];

        // Handle manage tab
        if ($tab === 'manage') {
            $query = Lead::with([
                'source',
                'stage',
                'user',
                'assignee',
                'creator',
                'activities' => function ($activityQuery) {
                    $activityQuery->latest()->take(5)->with('creator');
                },
            ]);

            // Search
            if ($request->filled('search')) {
                $term = $request->get('search');
                $query->where(function($q) use ($term) {
                    $q->where('name', 'like', "%$term%")
                      ->orWhere('email', 'like', "%$term%")
                      ->orWhere('phone', 'like', "%$term%");
                });
            }

            // Filter by source
            if ($request->filled('lead_source_id')) {
                $query->where('lead_source_id', $request->get('lead_source_id'));
            }

            // Filter by stage
            if ($request->filled('lead_stage_id')) {
                $query->where('lead_stage_id', $request->get('lead_stage_id'));
            }

            // Filter by assigned to
            if ($request->filled('assigned_to')) {
                $query->where('assigned_to', $request->get('assigned_to'));
            }

            $leads = $query->latest()->paginate(20)->withQueryString();
        }

        // Handle assign tab
        if ($tab === 'assign') {
            $query = Lead::with(['source', 'stage', 'user', 'assignee']);

            // Filter unassigned leads
            if ($request->filled('filter') && $request->get('filter') === 'unassigned') {
                $query->whereNull('assigned_to');
            }

            // Filter by source
            if ($request->filled('lead_source_id')) {
                $query->where('lead_source_id', $request->get('lead_source_id'));
            }

            // Filter by stage
            if ($request->filled('lead_stage_id')) {
                $query->where('lead_stage_id', $request->get('lead_stage_id'));
            }

            $assignLeads = $query->latest()->paginate(20)->withQueryString();
        }

        if (in_array($tab, ['today-followups', 'next-followups'])) {
            $todayFollowups = LeadFollowup::with(['lead.stage', 'lead.assignee'])
                ->scheduled()
                ->whereDate('followup_date', Carbon::today())
                ->orderBy('followup_time')
                ->paginate(20, ['*'], 'today_page');

            $upcomingFollowups = LeadFollowup::with(['lead.stage', 'lead.assignee'])
                ->scheduled()
                ->whereDate('followup_date', '>', Carbon::today())
                ->orderBy('followup_date')
                ->orderBy('followup_time')
                ->paginate(20, ['*'], 'upcoming_page');
        }

        if ($tab === 'trash') {
            $trashedLeads = Lead::onlyTrashed()
                ->with(['source', 'stage', 'assignee'])
                ->orderByDesc('deleted_at')
                ->paginate(20);
        }

        if ($tab === 'analytics') {
            $analytics = app(LeadAnalyticsService::class)->getOverview();
        }

        return view('admin.leads.index-tabbed', compact(
            'leads',
            'assignLeads',
            'leadSources',
            'leadStages',
            'users',
            'tab',
            'todayFollowups',
            'upcomingFollowups',
            'trashedLeads',
            'analytics'
        ));
    }

    public function create(): View
    {
        // Redirect to tabbed interface
        return redirect()->route('admin.leads.index', ['tab' => 'add']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'lead_source_id' => ['nullable', 'exists:lead_sources,id'],
            'lead_stage_id' => ['nullable', 'exists:lead_stages,id'],
            'expected_value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
            'next_followup_date' => ['nullable', 'date'],
            'next_followup_time' => ['nullable', 'date_format:H:i'],
            'lead_score' => ['nullable', 'integer', 'min:0'],
            'assigned_to' => ['nullable', 'exists:users,id', function ($attribute, $value, $fail) {
                if ($value) {
                    $user = User::find($value);
                    if (!$user || !$user->is_staff || $user->is_admin) {
                        $fail('Leads can only be assigned to staff members.');
                    }
                }
            }],
        ]);

        $data['created_by'] = auth()->id();

        $lead = Lead::create($data);
        app(LeadScoringService::class)->refresh($lead);

        // Trigger drip campaigns for new lead
        try {
            app(DripCampaignService::class)->triggerForLead($lead);
        } catch (\Exception $e) {
            // Log error but don't fail the lead creation
            \Log::error('Failed to trigger drip campaign for lead: ' . $e->getMessage());
        }

        $tab = $request->get('tab', 'add');
        return redirect()->route('admin.leads.index', ['tab' => $tab])
            ->with('success', 'Lead created successfully.');
    }

    public function edit(Lead $lead): View
    {
        $leadSources = LeadSource::where('is_active', true)->orderBy('name')->get();
        $leadStages = LeadStage::orderBy('sort_order')->get();
        $users = User::where('is_staff', true)->where('is_admin', false)->orderBy('name')->get();
        
        // If lead is assigned to a non-staff user, include them in the list for display
        if ($lead->assigned_to) {
            $currentAssignee = User::find($lead->assigned_to);
            if ($currentAssignee && (!$currentAssignee->is_staff || $currentAssignee->is_admin)) {
                $users = $users->push($currentAssignee);
            }
        }

        return view('admin.leads.edit', compact('lead', 'leadSources', 'leadStages', 'users'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'lead_source_id' => ['nullable', 'exists:lead_sources,id'],
            'lead_stage_id' => ['nullable', 'exists:lead_stages,id'],
            'expected_value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
            'next_followup_date' => ['nullable', 'date'],
            'next_followup_time' => ['nullable', 'date_format:H:i'],
            'lead_score' => ['nullable', 'integer', 'min:0'],
            'assigned_to' => ['nullable', 'exists:users,id', function ($attribute, $value, $fail) {
                if ($value) {
                    $user = User::find($value);
                    if (!$user || !$user->is_staff || $user->is_admin) {
                        $fail('Leads can only be assigned to staff members.');
                    }
                }
            }],
        ]);

        // If assigned_to is set but the user is not staff, clear it
        if (isset($data['assigned_to']) && $data['assigned_to']) {
            $assignee = User::find($data['assigned_to']);
            if (!$assignee || !$assignee->is_staff || $assignee->is_admin) {
                $data['assigned_to'] = null;
            }
        }

        $lead->update($data);
        app(LeadScoringService::class)->refresh($lead);

        $tab = $request->get('tab', 'manage');
        return redirect()->route('admin.leads.index', ['tab' => $tab])
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Request $request, Lead $lead): RedirectResponse
    {
        $lead->delete();

        $tab = $request->get('tab', 'manage');
        return redirect()->route('admin.leads.index', ['tab' => $tab])
            ->with('success', 'Lead deleted successfully.');
    }

    public function followups(Request $request): RedirectResponse
    {
        $tab = $request->get('tab', 'next-followups');

        return redirect()->route('admin.leads.index', ['tab' => $tab]);
    }

    public function trash(): RedirectResponse
    {
        return redirect()->route('admin.leads.index', ['tab' => 'trash']);
    }

    public function restore(int $leadId): RedirectResponse
    {
        $lead = Lead::onlyTrashed()->findOrFail($leadId);
        $lead->restore();

        return back()->with('success', 'Lead restored successfully.');
    }

    public function forceDelete(int $leadId): RedirectResponse
    {
        $lead = Lead::onlyTrashed()->findOrFail($leadId);
        $lead->forceDelete();

        return back()->with('success', 'Lead permanently deleted.');
    }

    public function updateScore(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'lead_score' => ['nullable', 'integer', 'min:0'],
            'mode' => ['nullable', 'in:auto,manual'],
        ]);

        if (($data['mode'] ?? 'auto') === 'auto') {
            $score = app(LeadScoringService::class)->refresh($lead);
        } else {
            $score = $data['lead_score'] ?? $lead->lead_score;
            $lead->update(['lead_score' => $score]);
        }

        return back()->with('success', 'Lead score updated successfully.');
    }

    public function export(Request $request, LeadImportExportService $service)
    {
        $filters = $request->only(['lead_stage_id', 'lead_source_id', 'assigned_to', 'search']);
        $format = $request->get('format', 'csv');

        return $service->export($filters, $format);
    }

    public function import(Request $request, LeadImportExportService $service): RedirectResponse
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
            'mode' => ['nullable', 'in:create,update,replace'],
        ]);

        $summary = $service->import($data['file'], $data['mode'] ?? 'create');

        $message = sprintf(
            'Import completed. Created: %d, Updated: %d, Skipped: %d',
            $summary['created'],
            $summary['updated'],
            $summary['skipped']
        );

        if (!empty($summary['errors'])) {
            $message .= '. Errors: ' . implode('; ', array_slice($summary['errors'], 0, 5));
        }

        return back()->with('success', $message);
    }

    public function assign(Request $request): View
    {
        // Redirect to tabbed interface
        return redirect()->route('admin.leads.index', ['tab' => 'assign']);
    }

    public function assignUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'lead_ids' => ['required', 'array'],
            'lead_ids.*' => ['exists:leads,id'],
            'assigned_to' => [
                'required', 
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if (!$user || !$user->is_staff || $user->is_admin) {
                        $fail('Leads can only be assigned to staff members.');
                    }
                }
            ],
        ]);

        Lead::whereIn('id', $request->lead_ids)
            ->update(['assigned_to' => $request->assigned_to]);

        $tab = $request->get('tab', 'assign');
        return redirect()->route('admin.leads.index', ['tab' => $tab])
            ->with('success', 'Leads assigned successfully.');
    }
}
