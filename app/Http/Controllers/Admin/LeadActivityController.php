<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadActivityController extends Controller
{
    public function index(Lead $lead): View
    {
        $activities = $lead->activities()
            ->with('creator')
            ->latest()
            ->paginate(20);

        return view('admin.leads.activities.index', compact('lead', 'activities'));
    }

    public function store(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'activity_type' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'meta' => ['nullable', 'array'],
        ]);

        $data['created_by'] = auth()->id();

        $lead->activities()->create($data);

        return back()->with('success', 'Activity logged successfully.');
    }

    public function destroy(Lead $lead, LeadActivity $activity): RedirectResponse
    {
        if ($activity->lead_id !== $lead->id) {
            abort(404);
        }

        $activity->delete();

        return back()->with('success', 'Activity removed successfully.');
    }
}

