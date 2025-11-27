<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LeadSourceController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'add');
        $leadSources = LeadSource::withCount('leads')
            ->orderBy('name')
            ->get();

        return view('admin.lead-sources.index-tabbed', compact('leadSources', 'tab'));
    }

    public function create(): View
    {
        // Redirect to tabbed interface
        return redirect()->route('admin.lead-sources.index', ['tab' => 'add']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:lead_sources,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:lead_sources,slug'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        LeadSource::create($data);

        $tab = $request->get('tab', 'add');
        return redirect()->route('admin.lead-sources.index', ['tab' => $tab])
            ->with('success', 'Lead source created successfully.');
    }

    public function edit(LeadSource $leadSource): View
    {
        return view('admin.lead-sources.edit', compact('leadSource'));
    }

    public function update(Request $request, LeadSource $leadSource): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:lead_sources,name,' . $leadSource->id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:lead_sources,slug,' . $leadSource->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $leadSource->update($data);

        $tab = $request->get('tab', 'manage');
        return redirect()->route('admin.lead-sources.index', ['tab' => $tab])
            ->with('success', 'Lead source updated successfully.');
    }

    public function destroy(Request $request, LeadSource $leadSource): RedirectResponse
    {
        if ($leadSource->leads()->count() > 0) {
            $tab = $request->get('tab', 'manage');
            return redirect()->route('admin.lead-sources.index', ['tab' => $tab])
                ->with('error', 'Cannot delete lead source that has associated leads.');
        }

        $leadSource->delete();

        $tab = $request->get('tab', 'manage');
        return redirect()->route('admin.lead-sources.index', ['tab' => $tab])
            ->with('success', 'Lead source deleted successfully.');
    }
}

