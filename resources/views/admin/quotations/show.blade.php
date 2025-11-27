@extends('layouts.admin')

@section('title', 'View Quotation')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Quotation #{{ $quotation->quote_number ?? $quotation->id }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.quotations.index') }}" class="hover-text-primary">Quotations</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">View</li>
		</ul>
	</div>

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Quotation Items</h6>
					<div class="table-responsive">
						<table class="table bordered-table mb-0">
							<thead>
								<tr>
									<th>Product</th>
									<th>Quantity</th>
									<th class="text-end">Price</th>
									<th class="text-end">Tax</th>
									<th class="text-end">Total</th>
								</tr>
							</thead>
							<tbody>
								@foreach($quotation->items as $item)
									<tr>
										<td>{{ $item->product->name ?? 'N/A' }}</td>
										<td>{{ $item->quantity }}</td>
										<td class="text-end">{{ currency($item->price) }}</td>
										<td class="text-end">{{ currency($item->tax_amount ?? 0) }}</td>
										<td class="text-end">{{ currency(($item->quantity * $item->price) + ($item->tax_amount ?? 0)) }}</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4" class="text-end fw-semibold">Total Amount:</td>
									<td class="text-end fw-semibold">{{ currency($quotation->total_amount) }}</td>
								</tr>
							</tfoot>
						</table>
					</div>

					@if($quotation->notes)
						<div class="mt-4">
							<h6 class="fw-semibold mb-2">Notes</h6>
							<p class="text-secondary-light">{{ $quotation->notes }}</p>
						</div>
					@endif
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Quotation Details</h6>
					<div class="mb-3">
						<label class="text-secondary-light small">Status</label>
						<div>
							<span class="badge bg-{{ $quotation->status === 'accepted' ? 'success' : ($quotation->status === 'rejected' ? 'danger' : ($quotation->status === 'sent' ? 'info' : 'secondary')) }}">
								{{ ucfirst($quotation->status ?? 'draft') }}
							</span>
						</div>
					</div>
					<div class="mb-3">
						<label class="text-secondary-light small">Lead</label>
						<div class="fw-medium">{{ $quotation->lead->name ?? '-' }}</div>
						@if($quotation->lead)
							<div class="text-sm text-secondary-light">{{ $quotation->lead->email ?? '' }}</div>
						@endif
					</div>
					<div class="mb-3">
						<label class="text-secondary-light small">Currency</label>
						<div class="fw-medium">{{ $quotation->currency }}</div>
					</div>
					@if($quotation->valid_until)
						<div class="mb-3">
							<label class="text-secondary-light small">Valid Until</label>
							<div class="fw-medium">{{ $quotation->valid_until->format('d M Y') }}</div>
						</div>
					@endif
					<div class="mb-3">
						<label class="text-secondary-light small">Created</label>
						<div class="fw-medium">{{ $quotation->created_at->format('d M Y H:i') }}</div>
					</div>
					<div class="mt-4">
						<a href="{{ route('admin.quotations.edit', $quotation) }}" class="btn btn-primary w-100 radius-12">Edit Quotation</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

