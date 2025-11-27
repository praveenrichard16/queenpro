<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceTemplate;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        if (!Schema::hasTable('invoices')) {
            $invoices = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1);
            return view('admin.invoices.index', compact('invoices'));
        }

        $query = Invoice::with(['order', 'template']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create(Request $request): View
    {
        $order = null;
        if ($request->filled('order_id')) {
            $order = Order::with(['orderItems.product'])->findOrFail($request->order_id);
        }

        $orders = Schema::hasTable('invoices') 
            ? Order::whereDoesntHave('invoice')->orderBy('created_at', 'desc')->get()
            : Order::orderBy('created_at', 'desc')->get();
        
        $templates = Schema::hasTable('invoice_templates')
            ? InvoiceTemplate::where('is_active', true)->orderBy('name')->get()
            : collect([]);
        
        // If order is selected, pre-fill form data
        if ($order) {
            // Pre-fill will be handled in the view
        }

        return view('admin.invoices.form', compact('order', 'orders', 'templates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order_id' => ['nullable', 'exists:orders,id'],
            'template_id' => ['nullable', 'exists:invoice_templates,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'billing_address' => ['required', 'array'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'shipping_amount' => ['nullable', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array'],
            'items.*.item_name' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.tax_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.total_price' => ['required', 'numeric', 'min:0'],
        ]);

        // Generate invoice number
        $data['invoice_number'] = 'INV-' . strtoupper(Str::random(8));
        $data['status'] = 'draft';

        $invoice = Invoice::create($data);

        // Create invoice items
        foreach ($data['items'] as $item) {
            $invoice->items()->create($item);
        }

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['order', 'template', 'items.product']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['template', 'items.product']);
        
        $template = $invoice->template ?? InvoiceTemplate::where('is_default', true)->first();
        
        $pdf = Pdf::loadView('admin.invoices.pdf', [
            'invoice' => $invoice,
            'template' => $template,
        ]);

        // Save PDF to storage
        $pdfPath = 'invoices/' . $invoice->invoice_number . '.pdf';
        \Storage::disk('public')->put($pdfPath, $pdf->output());
        
        // Update invoice with PDF path
        $invoice->update(['pdf_path' => $pdfPath]);

        // Download the PDF
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    public function updateStatus(Request $request, Invoice $invoice): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:draft,sent,paid,overdue,cancelled'],
        ]);

        $invoice->update(['status' => $request->status]);

        return back()->with('success', 'Invoice status updated successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}

