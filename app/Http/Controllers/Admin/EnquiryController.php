<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Enquiry::with(['user', 'product', 'lead']);

        // Search
        if ($request->filled('search')) {
            $term = $request->get('search');
            $query->where(function($q) use ($term) {
                $q->where('subject', 'like', "%$term%")
                  ->orWhere('message', 'like', "%$term%")
                  ->orWhereHas('user', function($q) use ($term) {
                      $q->where('name', 'like', "%$term%")
                        ->orWhere('email', 'like', "%$term%");
                  })
                  ->orWhereHas('product', function($q) use ($term) {
                      $q->where('name', 'like', "%$term%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        $enquiries = $query->latest()->paginate(20)->withQueryString();

        return view('admin.enquiries.index', compact('enquiries'));
    }

    public function show(Enquiry $enquiry): View
    {
        $enquiry->load(['user', 'product', 'lead']);

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function updateStatus(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:new,in_progress,converted,closed'],
        ]);

        $enquiry->update($data);

        return redirect()->route('admin.enquiries.show', $enquiry)
            ->with('success', 'Enquiry status updated successfully.');
    }

    public function convertToLead(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $data = $request->validate([
            'lead_source_id' => ['nullable', 'exists:lead_sources,id'],
            'lead_stage_id' => ['nullable', 'exists:lead_stages,id'],
            'expected_value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        // Get default stage if not provided
        if (empty($data['lead_stage_id'])) {
            $defaultStage = LeadStage::where('is_default', true)->first();
            if ($defaultStage) {
                $data['lead_stage_id'] = $defaultStage->id;
            }
        }

        $lead = Lead::create([
            'user_id' => $enquiry->user_id,
            'name' => $enquiry->user->name ?? 'Unknown',
            'email' => $enquiry->user->email ?? null,
            'phone' => $enquiry->user->phone ?? null,
            'lead_source_id' => $data['lead_source_id'] ?? null,
            'lead_stage_id' => $data['lead_stage_id'] ?? null,
            'expected_value' => $data['expected_value'] ?? null,
            'notes' => ($data['notes'] ?? '') . "\n\nConverted from enquiry: " . $enquiry->subject,
            'created_by' => auth()->id(),
        ]);

        // Update enquiry
        $enquiry->update([
            'status' => 'converted',
            'converted_to_lead_id' => $lead->id,
        ]);

        return redirect()->route('admin.leads.edit', $lead)
            ->with('success', 'Enquiry converted to lead successfully.');
    }
}

