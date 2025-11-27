<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingMethodController extends Controller
{
    public function index(): View
    {
        $shippingMethods = ShippingMethod::orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('admin.shipping-methods.index', compact('shippingMethods'));
    }

    public function create(): View
    {
        return view('admin.shipping-methods.form', [
            'shippingMethod' => new ShippingMethod(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:shipping_methods,code'],
            'type' => ['required', 'in:flat_rate,free_shipping,weight_based,location_based'],
            'cost' => ['required', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'settings' => ['nullable', 'array'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['settings'] = $data['settings'] ?? [];

        // Convert empty strings to null for nullable fields
        $data['free_shipping_threshold'] = $data['free_shipping_threshold'] ?? null;
        $data['free_shipping_threshold'] = $data['free_shipping_threshold'] === '' ? null : $data['free_shipping_threshold'];

        ShippingMethod::create($data);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method created successfully.');
    }

    public function edit(ShippingMethod $shippingMethod): View
    {
        return view('admin.shipping-methods.form', compact('shippingMethod'));
    }

    public function update(Request $request, ShippingMethod $shippingMethod): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:shipping_methods,code,' . $shippingMethod->id],
            'type' => ['required', 'in:flat_rate,free_shipping,weight_based,location_based'],
            'cost' => ['required', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'settings' => ['nullable', 'array'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? $shippingMethod->sort_order;
        $data['settings'] = $data['settings'] ?? $shippingMethod->settings ?? [];

        // Convert empty strings to null for nullable fields
        $data['free_shipping_threshold'] = $data['free_shipping_threshold'] ?? null;
        $data['free_shipping_threshold'] = $data['free_shipping_threshold'] === '' ? null : $data['free_shipping_threshold'];

        $shippingMethod->update($data);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->delete();

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method deleted successfully.');
    }
}

