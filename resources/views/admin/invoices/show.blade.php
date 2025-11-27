@extends('layouts.admin')

@section('title', 'Invoice Details')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Invoice #{{ $invoice->invoice_number }}</h6>
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
			<li class="fw-medium text-secondary-light">Details</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<div class="d-flex justify-content-between align-items-start mb-24">
						<div>
							<h5 class="fw-semibold mb-2">Invoice #{{ $invoice->invoice_number }}</h5>
							<p class="text-sm text-secondary-light mb-0">Date: {{ $invoice->invoice_date->format('M d, Y') }}</p>
							@if($invoice->due_date)
								<p class="text-sm text-secondary-light mb-0">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
							@endif
						</div>
						<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ match($invoice->status) {
							'paid' => 'bg-success-focus text-success-main',
							'sent' => 'bg-info-focus text-info-main',
							'overdue' => 'bg-danger-focus text-danger-main',
							default => 'bg-neutral-200 text-neutral-600'
						} }}">
							{{ ucfirst($invoice->status) }}
						</span>
					</div>

					<div class="row mb-24">
						<div class="col-md-6">
							<h6 class="fw-semibold mb-12">Bill To:</h6>
							<p class="mb-1 fw-medium">{{ $invoice->customer_name }}</p>
							<p class="mb-1 text-sm text-secondary-light">{{ $invoice->customer_email }}</p>
							@if($invoice->billing_address)
								<p class="mb-0 text-sm text-secondary-light">
									{{ $invoice->billing_address['street'] ?? '' }}<br>
									{{ $invoice->billing_address['city'] ?? '' }}, {{ $invoice->billing_address['state'] ?? '' }} {{ $invoice->billing_address['zip'] ?? '' }}
								</p>
							@endif
						</div>
					</div>

					<div class="table-responsive mb-24">
						<table class="table">
							<thead>
								<tr>
									<th>Item</th>
									<th>Qty</th>
									<th class="text-end">Price</th>
									<th class="text-end">Tax</th>
									<th class="text-end">Total</th>
								</tr>
							</thead>
							<tbody>
								@foreach($invoice->items as $item)
									<tr>
										<td>{{ $item->item_name }}</td>
										<td>{{ $item->quantity }}</td>
										<td class="text-end">{{ \App\Services\CurrencyService::format($item->unit_price) }}</td>
										<td class="text-end">{{ \App\Services\CurrencyService::format($item->tax_amount) }}</td>
										<td class="text-end fw-semibold">{{ \App\Services\CurrencyService::format($item->total_price) }}</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4" class="text-end">Subtotal:</td>
									<td class="text-end fw-semibold">{{ \App\Services\CurrencyService::format($invoice->subtotal) }}</td>
								</tr>
								@if($invoice->tax_amount > 0)
									<tr>
										<td colspan="4" class="text-end">Tax:</td>
										<td class="text-end">{{ \App\Services\CurrencyService::format($invoice->tax_amount) }}</td>
									</tr>
								@endif
								@if($invoice->shipping_amount > 0)
									<tr>
										<td colspan="4" class="text-end">Shipping:</td>
										<td class="text-end">{{ \App\Services\CurrencyService::format($invoice->shipping_amount) }}</td>
									</tr>
								@endif
								@if($invoice->discount_amount > 0)
									<tr>
										<td colspan="4" class="text-end">Discount:</td>
										<td class="text-end text-danger">-{{ \App\Services\CurrencyService::format($invoice->discount_amount) }}</td>
									</tr>
								@endif
								<tr>
									<td colspan="4" class="text-end fw-semibold">Total:</td>
									<td class="text-end fw-bold text-lg">{{ \App\Services\CurrencyService::format($invoice->total_amount) }}</td>
								</tr>
							</tfoot>
						</table>
					</div>

					@if($invoice->notes)
						<div class="bg-neutral-50 p-16 radius-12">
							<h6 class="fw-semibold mb-8">Notes:</h6>
							<p class="mb-0 text-sm">{{ $invoice->notes }}</p>
						</div>
					@endif
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Actions</h6>
					<div class="d-flex flex-column gap-2">
						<a href="{{ route('admin.invoices.invoices.pdf', $invoice) }}" target="_blank" class="btn btn-primary w-100 radius-12">
							<iconify-icon icon="solar:document-text-outline"></iconify-icon> Download PDF
						</a>
						<form action="{{ route('admin.invoices.invoices.status', $invoice) }}" method="POST" class="d-inline">
							@csrf
							<select name="status" class="form-select mb-2" onchange="this.form.submit()">
								<option value="draft" @selected($invoice->status === 'draft')>Draft</option>
								<option value="sent" @selected($invoice->status === 'sent')>Sent</option>
								<option value="paid" @selected($invoice->status === 'paid')>Paid</option>
								<option value="overdue" @selected($invoice->status === 'overdue')>Overdue</option>
								<option value="cancelled" @selected($invoice->status === 'cancelled')>Cancelled</option>
							</select>
						</form>
					</div>
				</div>
			</div>

			@if($invoice->order)
				<div class="card border-0">
					<div class="card-body p-24">
						<h6 class="fw-semibold mb-16">Related Order</h6>
						<p class="mb-2"><strong>Order #:</strong> <a href="{{ route('admin.orders.show', $invoice->order) }}">{{ $invoice->order->order_number }}</a></p>
						<p class="mb-0"><strong>Status:</strong> {{ ucfirst($invoice->order->status) }}</p>
					</div>
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

