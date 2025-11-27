@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Invoices</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Invoices</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<div class="d-flex justify-content-between align-items-center">
				<form method="GET" class="d-flex gap-3">
					<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="{{ request('search') }}" placeholder="Invoice number, customer">
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status" onchange="this.form.submit()">
						<option value="">All statuses</option>
						<option value="draft" @selected(request('status') === 'draft')>Draft</option>
						<option value="sent" @selected(request('status') === 'sent')>Sent</option>
						<option value="paid" @selected(request('status') === 'paid')>Paid</option>
						<option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
					</select>
					<button class="btn btn-primary h-56-px radius-12">Filter</button>
				</form>
				<a href="{{ route('admin.invoices.invoices.create') }}" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
					<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					Create Invoice
				</a>
			</div>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Invoice #</th>
							<th>Customer</th>
							<th>Order</th>
							<th>Date</th>
							<th>Due Date</th>
							<th>Amount</th>
							<th>Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($invoices as $invoice)
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium">{{ $invoice->invoice_number }}</h6>
								</td>
								<td>
									<div>
										<h6 class="text-sm mb-1 fw-medium">{{ $invoice->customer_name }}</h6>
										<p class="text-xs text-secondary-light mb-0">{{ $invoice->customer_email }}</p>
									</div>
								</td>
								<td>
									@if($invoice->order)
										<a href="{{ route('admin.orders.show', $invoice->order) }}" class="text-primary">{{ $invoice->order->order_number }}</a>
									@else
										<span class="text-secondary-light">—</span>
									@endif
								</td>
								<td>
									<span class="text-sm">{{ $invoice->invoice_date->format('M d, Y') }}</span>
								</td>
								<td>
									@if($invoice->due_date)
										<span class="text-sm">{{ $invoice->due_date->format('M d, Y') }}</span>
									@else
										<span class="text-secondary-light">—</span>
									@endif
								</td>
								<td>
									<span class="fw-semibold">{{ \App\Services\CurrencyService::format($invoice->total_amount) }}</span>
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ match($invoice->status) {
										'paid' => 'bg-success-focus text-success-main',
										'sent' => 'bg-info-focus text-info-main',
										'overdue' => 'bg-danger-focus text-danger-main',
										default => 'bg-neutral-200 text-neutral-600'
									} }}">
										{{ ucfirst($invoice->status) }}
									</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.invoices.invoices.show', $invoice) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="solar:eye-bold"></iconify-icon>
										</a>
										<a href="{{ route('admin.invoices.invoices.pdf', $invoice) }}" class="w-36-px h-36-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center" target="_blank">
											<iconify-icon icon="solar:document-text-outline"></iconify-icon>
										</a>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="8" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No invoices" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No invoices found</h6>
									<p class="text-secondary-light mb-0">Create your first invoice.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($invoices->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $invoices->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

