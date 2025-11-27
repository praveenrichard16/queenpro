@extends('layouts.admin')

@section('title', 'Manage Quotations')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Manage Quotations</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Quotations</li>
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
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="{{ request('search') }}" placeholder="Quote number, lead name">
					</div>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All Statuses</option>
						<option value="draft" @selected(request('status') === 'draft')>Draft</option>
						<option value="sent" @selected(request('status') === 'sent')>Sent</option>
						<option value="accepted" @selected(request('status') === 'accepted')>Accepted</option>
						<option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
						<option value="expired" @selected(request('status') === 'expired')>Expired</option>
					</select>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Lead</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_id">
						<option value="">All Leads</option>
						@foreach($leads as $lead)
							<option value="{{ $lead->id }}" @selected(request('lead_id') == $lead->id)>{{ $lead->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.quotations.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-lg-12">
					<a href="{{ route('admin.quotations.create') }}" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add Quotation
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Quote #</th>
							<th>Lead</th>
							<th>Status</th>
							<th>Total</th>
							<th>Valid Until</th>
							<th>Created</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($quotations as $quotation)
							<tr>
								<td>{{ $quotation->quote_number ?? $quotation->id }}</td>
								<td>{{ $quotation->lead?->name ?? '-' }}</td>
								<td>
									<span class="badge bg-{{ $quotation->status === 'accepted' ? 'success' : ($quotation->status === 'rejected' ? 'danger' : ($quotation->status === 'sent' ? 'info' : 'secondary')) }}">
										{{ ucfirst($quotation->status ?? 'draft') }}
									</span>
								</td>
								<td>{{ isset($quotation->total_amount) ? currency($quotation->total_amount) : '-' }}</td>
								<td>{{ $quotation->valid_until?->format('d M Y') ?? '-' }}</td>
								<td>{{ $quotation->created_at?->format('d M Y H:i') }}</td>
								<td>
									<div class="d-flex align-items-center gap-2">
										<a href="{{ route('admin.quotations.show', $quotation) }}" class="btn btn-sm btn-outline-info" title="View">
											<iconify-icon icon="solar:eye-outline"></iconify-icon>
										</a>
										<a href="{{ route('admin.quotations.edit', $quotation) }}" class="btn btn-sm btn-outline-primary" title="Edit">
											<iconify-icon icon="solar:pen-outline"></iconify-icon>
										</a>
										<form action="{{ route('admin.quotations.destroy', $quotation) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this quotation?');">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
												<iconify-icon icon="solar:trash-bin-outline"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="text-center py-4 text-secondary-light">No quotations found.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			@if($quotations->hasPages())
				<div class="p-3">
					{{ $quotations->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection
