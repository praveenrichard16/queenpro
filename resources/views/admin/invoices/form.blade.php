@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Create Invoice</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.invoices.invoices.index') }}" class="hover-text-primary">Invoices</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Create</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.invoices.invoices.store') }}" class="row g-4" id="invoiceForm">
				@csrf

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Select Order (Optional)</label>
					<select name="order_id" id="orderSelect" class="form-select bg-neutral-50 radius-12 h-56-px">
						<option value="">Create from order</option>
						@foreach($orders as $ord)
							<option value="{{ $ord->id }}" data-order='@json($ord->toArray())'>{{ $ord->order_number }} - {{ $ord->customer_name }}</option>
						@endforeach
					</select>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Template</label>
					<select name="template_id" class="form-select bg-neutral-50 radius-12 h-56-px">
						<option value="">Use default template</option>
						@foreach($templates as $template)
							<option value="{{ $template->id }}">{{ $template->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Invoice Date <span class="text-danger">*</span></label>
					<input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="form-control bg-neutral-50 radius-12 h-56-px" required>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Due Date</label>
					<input type="date" name="due_date" value="{{ old('due_date') }}" class="form-control bg-neutral-50 radius-12 h-56-px">
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Customer Name <span class="text-danger">*</span></label>
					<input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $order->customer_name ?? '') }}" class="form-control bg-neutral-50 radius-12 h-56-px" required>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Customer Email <span class="text-danger">*</span></label>
					<input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $order->customer_email ?? '') }}" class="form-control bg-neutral-50 radius-12 h-56-px" required>
				</div>

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Billing Address</h6>
					<div class="row g-3">
						<div class="col-12">
							<input type="text" name="billing_address[street]" placeholder="Street Address" value="{{ old('billing_address.street', $order->billing_address['street'] ?? '') }}" class="form-control bg-neutral-50 radius-12 h-56-px">
						</div>
						<div class="col-md-4">
							<input type="text" name="billing_address[city]" placeholder="City" value="{{ old('billing_address.city', $order->billing_address['city'] ?? '') }}" class="form-control bg-neutral-50 radius-12 h-56-px">
						</div>
						<div class="col-md-4">
							<input type="text" name="billing_address[state]" placeholder="State" value="{{ old('billing_address.state', $order->billing_address['state'] ?? '') }}" class="form-control bg-neutral-50 radius-12 h-56-px">
						</div>
						<div class="col-md-4">
							<input type="text" name="billing_address[zip]" placeholder="ZIP Code" value="{{ old('billing_address.zip', $order->billing_address['zip'] ?? '') }}" class="form-control bg-neutral-50 radius-12 h-56-px">
						</div>
					</div>
				</div>

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Invoice Items</h6>
					<div id="invoiceItems">
						@php
							$itemIndex = 0;
							if($order && $order->orderItems && $order->orderItems->count() > 0) {
								$itemIndex = $order->orderItems->count();
							}
						@endphp
						@if($order && $order->orderItems && $order->orderItems->count() > 0)
							@foreach($order->orderItems as $index => $item)
								<div class="row g-3 mb-3 item-row">
									<div class="col-md-4">
										<input type="text" name="items[{{ $index }}][item_name]" value="{{ $item->product->name ?? 'Item' }}" class="form-control item-name" placeholder="Item name" required>
									</div>
									<div class="col-md-2">
										<input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control item-qty" placeholder="Qty" min="1" required>
									</div>
									<div class="col-md-2">
										<input type="number" step="0.01" name="items[{{ $index }}][unit_price]" value="{{ $item->price }}" class="form-control item-price" placeholder="Price" min="0" required>
									</div>
									<div class="col-md-2">
										<input type="number" step="0.01" name="items[{{ $index }}][tax_amount]" value="{{ $item->tax_amount ?? 0 }}" class="form-control item-tax" placeholder="Tax" min="0">
									</div>
									<div class="col-md-2">
										<input type="number" step="0.01" name="items[{{ $index }}][total_price]" value="{{ ($item->price * $item->quantity) + ($item->tax_amount ?? 0) }}" class="form-control item-total" placeholder="Total" readonly>
									</div>
								</div>
							@endforeach
						@else
							<div class="row g-3 mb-3 item-row">
								<div class="col-md-4">
									<input type="text" name="items[0][item_name]" class="form-control item-name" placeholder="Item name" required>
								</div>
								<div class="col-md-2">
									<input type="number" name="items[0][quantity]" value="1" class="form-control item-qty" placeholder="Qty" min="1" required>
								</div>
								<div class="col-md-2">
									<input type="number" step="0.01" name="items[0][unit_price]" class="form-control item-price" placeholder="Price" min="0" required>
								</div>
								<div class="col-md-2">
									<input type="number" step="0.01" name="items[0][tax_amount]" value="0" class="form-control item-tax" placeholder="Tax" min="0">
								</div>
								<div class="col-md-2">
									<input type="number" step="0.01" name="items[0][total_price]" class="form-control item-total" placeholder="Total" readonly>
								</div>
							</div>
						@endif
					</div>
					<button type="button" class="btn btn-outline-primary btn-sm" id="addItem">Add Item</button>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Subtotal</label>
					<input type="number" step="0.01" name="subtotal" id="subtotal" value="{{ old('subtotal', $order->subtotal ?? 0) }}" class="form-control bg-neutral-50 radius-12 h-56-px" readonly>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Tax Amount</label>
					<input type="number" step="0.01" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', $order->tax_amount ?? 0) }}" class="form-control bg-neutral-50 radius-12 h-56-px">
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Shipping Amount</label>
					<input type="number" step="0.01" name="shipping_amount" id="shipping_amount" value="{{ old('shipping_amount', $order->shipping_amount ?? 0) }}" class="form-control bg-neutral-50 radius-12 h-56-px">
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Discount Amount</label>
					<input type="number" step="0.01" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', 0) }}" class="form-control bg-neutral-50 radius-12 h-56-px">
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Total Amount <span class="text-danger">*</span></label>
					<input type="number" step="0.01" name="total_amount" id="total_amount" value="{{ old('total_amount', $order->total_amount ?? 0) }}" class="form-control bg-neutral-50 radius-12 h-56-px" required readonly>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Notes</label>
					<textarea name="notes" rows="3" class="form-control bg-neutral-50 radius-12">{{ old('notes') }}</textarea>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">Create Invoice</button>
					<a href="{{ route('admin.invoices.invoices.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

@push('scripts')
<script>
let itemIndex = {{ $itemIndex ?? ($order && $order->orderItems ? $order->orderItems->count() : 1) }};

document.getElementById('addItem')?.addEventListener('click', function() {
	const itemsContainer = document.getElementById('invoiceItems');
	const newRow = document.createElement('div');
	newRow.className = 'row g-3 mb-3 item-row';
	newRow.innerHTML = `
		<div class="col-md-4">
			<input type="text" name="items[${itemIndex}][item_name]" class="form-control" placeholder="Item name" required>
		</div>
		<div class="col-md-2">
			<input type="number" name="items[${itemIndex}][quantity]" value="1" class="form-control item-qty" placeholder="Qty" min="1" required>
		</div>
		<div class="col-md-2">
			<input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control item-price" placeholder="Price" min="0" required>
		</div>
		<div class="col-md-2">
			<input type="number" step="0.01" name="items[${itemIndex}][tax_amount]" value="0" class="form-control item-tax" placeholder="Tax" min="0">
		</div>
		<div class="col-md-2">
			<input type="number" step="0.01" name="items[${itemIndex}][total_price]" class="form-control item-total" placeholder="Total" readonly>
		</div>
	`;
	itemsContainer.appendChild(newRow);
	itemIndex++;
});

document.getElementById('invoiceForm')?.addEventListener('input', function(e) {
	if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price') || e.target.classList.contains('item-tax')) {
		const row = e.target.closest('.item-row');
		if (row) {
			const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
			const price = parseFloat(row.querySelector('.item-price')?.value) || 0;
			const tax = parseFloat(row.querySelector('.item-tax')?.value) || 0;
			const total = (qty * price) + tax;
			const totalInput = row.querySelector('.item-total');
			if (totalInput) {
				totalInput.value = total.toFixed(2);
			}
			calculateTotals();
		}
	}
	
	if (e.target.id === 'tax_amount' || e.target.id === 'shipping_amount' || e.target.id === 'discount_amount') {
		calculateTotals();
	}
});

function calculateTotals() {
	let subtotal = 0;
	document.querySelectorAll('.item-total').forEach(input => {
		subtotal += parseFloat(input.value) || 0;
	});
	
	const tax = parseFloat(document.getElementById('tax_amount')?.value) || 0;
	const shipping = parseFloat(document.getElementById('shipping_amount')?.value) || 0;
	const discount = parseFloat(document.getElementById('discount_amount')?.value) || 0;
	
	const subtotalEl = document.getElementById('subtotal');
	const totalEl = document.getElementById('total_amount');
	if (subtotalEl) subtotalEl.value = subtotal.toFixed(2);
	if (totalEl) totalEl.value = (subtotal + tax + shipping - discount).toFixed(2);
}

// Initialize totals on page load
document.addEventListener('DOMContentLoaded', function() {
	calculateTotals();
});

document.getElementById('orderSelect')?.addEventListener('change', function() {
	if (this.value) {
		// Redirect to create invoice with order_id
		window.location.href = '{{ route("admin.invoices.invoices.create") }}?order_id=' + this.value;
	}
});
</script>
@endpush
@endsection

