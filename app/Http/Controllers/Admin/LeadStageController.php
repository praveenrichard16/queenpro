<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadStage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LeadStageController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->get('tab', 'add');
        $leadStages = LeadStage::withCount('leads')
            ->orderBy('sort_order')
            ->get();

        return view('admin.lead-stages.index-tabbed', compact('leadStages', 'tab'));
    }

    public function create(): View
    {
        // Redirect to tabbed interface
        return redirect()->route('admin.lead-stages.index', ['tab' => 'add']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:lead_stages,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:lead_stages,slug'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_default' => ['sometimes', 'boolean'],
            'is_won' => ['sometimes', 'boolean'],
            'is_lost' => ['sometimes', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // If this is set as default, unset other defaults
        if ($request->boolean('is_default')) {
            LeadStage::where('is_default', true)->update(['is_default' => false]);
        }

        // If this is set as won, unset other won stages
        if ($request->boolean('is_won')) {
            LeadStage::where('is_won', true)->update(['is_won' => false]);
        }

        // If this is set as lost, unset other lost stages
        if ($request->boolean('is_lost')) {
            LeadStage::where('is_lost', true)->update(['is_lost' => false]);
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_default'] = $request->boolean('is_default', false);
        $data['is_won'] = $request->boolean('is_won', false);
        $data['is_lost'] = $request->boolean('is_lost', false);

        LeadStage::create($data);

        $tab = $request->get('tab', 'add');
        return redirect()->route('admin.lead-stages.index', ['tab' => $tab])
            ->with('success', 'Lead stage created successfully.');
    }

    public function edit(LeadStage $leadStage): View
    {
        return view('admin.lead-stages.edit', compact('leadStage'));
    }

    public function update(Request $request, LeadStage $leadStage): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:lead_stages,name,' . $leadStage->id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:lead_stages,slug,' . $leadStage->id],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_default' => ['sometimes', 'boolean'],
            'is_won' => ['sometimes', 'boolean'],
            'is_lost' => ['sometimes', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // If this is set as default, unset other defaults
        if ($request->boolean('is_default') && !$leadStage->is_default) {
            LeadStage::where('is_default', true)->where('id', '!=', $leadStage->id)->update(['is_default' => false]);
        }

        // If this is set as won, unset other won stages
        if ($request->boolean('is_won') && !$leadStage->is_won) {
            LeadStage::where('is_won', true)->where('id', '!=', $leadStage->id)->update(['is_won' => false]);
        }

        // If this is set as lost, unset other lost stages
        if ($request->boolean('is_lost') && !$leadStage->is_lost) {
            LeadStage::where('is_lost', true)->where('id', '!=', $leadStage->id)->update(['is_lost' => false]);
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_default'] = $request->boolean('is_default', false);
        $data['is_won'] = $request->boolean('is_won', false);
        $data['is_lost'] = $request->boolean('is_lost', false);

        $leadStage->update($data);

        $tab = $request->get('tab', 'manage');
        return redirect()->route('admin.lead-stages.index', ['tab' => $tab])
            ->with('success', 'Lead stage updated successfully.');
    }

    public function destroy(Request $request, LeadStage $leadStage): RedirectResponse
    {
        if ($leadStage->leads()->count() > 0) {
            $tab = $request->get('tab', 'manage');
            return redirect()->route('admin.lead-stages.index', ['tab' => $tab])
                ->with('error', 'Cannot delete lead stage that has associated leads.');
        }

        $leadStage->delete();

        $tab = $request->get('tab', 'manage');
        return redirect()->route('admin.lead-stages.index', ['tab' => $tab])
            ->with('success', 'Lead stage deleted successfully.');
    }
}

