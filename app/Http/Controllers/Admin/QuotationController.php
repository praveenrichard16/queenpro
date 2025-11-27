<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Quotation::with(['lead', 'items']);

        // Search
        if ($request->filled('search')) {
            $term = $request->get('search');
            $query->where(function($q) use ($term) {
                $q->where('quote_number', 'like', "%$term%")
                  ->orWhereHas('lead', function($q) use ($term) {
                      $q->where('name', 'like', "%$term%")
                        ->orWhere('email', 'like', "%$term%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by lead
        if ($request->filled('lead_id')) {
            $query->where('lead_id', $request->get('lead_id'));
        }

        $quotations = $query->latest()->paginate(20)->withQueryString();
        $leads = Lead::orderBy('name')->get();

        return view('admin.quotations.index', compact('quotations', 'leads'));
    }

    public function create(): View
    {
        $quotation = new Quotation();
        $leads = Lead::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.quotations.create', compact('quotation', 'leads', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lead_id' => ['required', 'exists:leads,id'],
            'quote_number' => ['nullable', 'string', 'max:255', 'unique:quotations,quote_number'],
            'status' => ['required', 'string', 'in:draft,sent,accepted,rejected,expired'],
            'currency' => ['required', 'string', 'max:3'],
            'valid_until' => ['nullable', 'date', 'after:today'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.tax_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
        ]);

        // Generate quote number if not provided
        if (empty($data['quote_number'])) {
            $data['quote_number'] = 'QT-' . str_pad(Quotation::max('id') + 1, 6, '0', STR_PAD_LEFT);
        }

        // Calculate total
        $total = 0;
        foreach ($data['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['price'];
            if (isset($item['tax_amount'])) {
                $itemTotal += $item['tax_amount'];
            }
            $total += $itemTotal;
        }
        $data['total_amount'] = $total;

        $quotation = Quotation::create($data);

        // Create quotation items
        foreach ($data['items'] as $item) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'tax_amount' => $item['tax_amount'] ?? null,
            ]);
        }

        return redirect()->route('admin.quotations.index')
            ->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation): View
    {
        $quotation->load(['lead', 'items.product']);

        return view('admin.quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation): View
    {
        $quotation->load('items');
        $leads = Lead::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.quotations.edit', compact('quotation', 'leads', 'products'));
    }

    public function update(Request $request, Quotation $quotation): RedirectResponse
    {
        $data = $request->validate([
            'lead_id' => ['required', 'exists:leads,id'],
            'quote_number' => ['nullable', 'string', 'max:255', 'unique:quotations,quote_number,' . $quotation->id],
            'status' => ['required', 'string', 'in:draft,sent,accepted,rejected,expired'],
            'currency' => ['required', 'string', 'max:3'],
            'valid_until' => ['nullable', 'date', 'after:today'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.tax_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
        ]);

        // Calculate total
        $total = 0;
        foreach ($data['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['price'];
            if (isset($item['tax_amount'])) {
                $itemTotal += $item['tax_amount'];
            }
            $total += $itemTotal;
        }
        $data['total_amount'] = $total;

        $quotation->update($data);

        // Delete existing items
        $quotation->items()->delete();

        // Create new quotation items
        foreach ($data['items'] as $item) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'tax_amount' => $item['tax_amount'] ?? null,
            ]);
        }

        return redirect()->route('admin.quotations.index')
            ->with('success', 'Quotation updated successfully.');
    }

    public function destroy(Quotation $quotation): RedirectResponse
    {
        $quotation->items()->delete();
        $quotation->delete();

        return redirect()->route('admin.quotations.index')
            ->with('success', 'Quotation deleted successfully.');
    }
}
