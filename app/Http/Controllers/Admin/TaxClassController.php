<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxClassController extends Controller
{
    public function index(): View
    {
        $taxClasses = TaxClass::orderBy('name')->paginate(20);
        return view('admin.tax-classes.index', compact('taxClasses'));
    }

    public function create(): View
    {
        return view('admin.tax-classes.form', [
            'taxClass' => new TaxClass(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tax_classes,name'],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        TaxClass::create($data);

        return redirect()->route('admin.tax-classes.index')
            ->with('success', 'Tax class created successfully.');
    }

    public function edit(TaxClass $taxClass): View
    {
        return view('admin.tax-classes.form', compact('taxClass'));
    }

    public function update(Request $request, TaxClass $taxClass): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tax_classes,name,' . $taxClass->id],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $taxClass->update($data);

        return redirect()->route('admin.tax-classes.index')
            ->with('success', 'Tax class updated successfully.');
    }

    public function destroy(TaxClass $taxClass): RedirectResponse
    {
        $taxClass->delete();

        return redirect()->route('admin.tax-classes.index')
            ->with('success', 'Tax class deleted successfully.');
    }
}
