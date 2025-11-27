<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class InvoiceTemplateController extends Controller
{
    public function index(): View
    {
        if (!Schema::hasTable('invoice_templates')) {
            $templates = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1);
            return view('admin.invoices.templates.index', compact('templates'));
        }

        $templates = InvoiceTemplate::orderBy('is_default', 'desc')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.invoices.templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('admin.invoices.templates.form', [
            'template' => new InvoiceTemplate(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'header_html' => ['nullable', 'string'],
            'footer_html' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
            'is_default' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_default'] = $request->boolean('is_default', false);
        $data['settings'] = $data['settings'] ?? [];

        // If this is set as default, unset others
        if ($data['is_default']) {
            InvoiceTemplate::where('is_default', true)->update(['is_default' => false]);
        }

        InvoiceTemplate::create($data);

        return redirect()->route('admin.invoices.templates.index')
            ->with('success', 'Invoice template created successfully.');
    }

    public function edit(InvoiceTemplate $template): View
    {
        return view('admin.invoices.templates.form', compact('template'));
    }

    public function update(Request $request, InvoiceTemplate $template): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'header_html' => ['nullable', 'string'],
            'footer_html' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
            'is_default' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_default'] = $request->boolean('is_default', false);
        $data['settings'] = $data['settings'] ?? $template->settings ?? [];

        // If this is set as default, unset others
        if ($data['is_default'] && !$template->is_default) {
            InvoiceTemplate::where('is_default', true)->update(['is_default' => false]);
        }

        $template->update($data);

        return redirect()->route('admin.invoices.templates.index')
            ->with('success', 'Invoice template updated successfully.');
    }

    public function destroy(InvoiceTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('admin.invoices.templates.index')
            ->with('success', 'Invoice template deleted successfully.');
    }
}

