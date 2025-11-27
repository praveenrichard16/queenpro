@extends('layouts.admin')

@section('title', 'Affiliate Commissions')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Affiliate Commissions</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Commissions</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif
	@if(session('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ session('error') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All statuses</option>
						<option value="pending" @selected(request('status') === 'pending')>Pending</option>
						<option value="approved" @selected(request('status') === 'approved')>Approved</option>
						<option value="paid" @selected(request('status') === 'paid')>Paid</option>
						<option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.affiliates.commissions.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
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
							<th>Affiliate</th>
							<th>Order</th>
							<th class="text-end">Amount</th>
							<th>Status</th>
							<th>Date</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($commissions as $commission)
							<tr>
								<td>
									<h6 class="text-md mb-2 fw-medium">{{ $commission->affiliate->user->name }}</h6>
									<p class="text-sm text-secondary-light mb-0">{{ $commission->affiliate->affiliate_code }}</p>
								</td>
								<td>
									<a href="{{ route('admin.orders.show', $commission->order) }}" class="text-primary">
										{{ $commission->order->order_number }}
									</a>
									<p class="text-sm text-secondary-light mb-0">{{ \App\Services\CurrencyService::format($commission->order->total_amount) }}</p>
								</td>
								<td class="text-end fw-semibold">{{ \App\Services\CurrencyService::format($commission->amount) }}</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm 
										{{ $commission->status === 'paid' ? 'bg-success-focus text-success-main' : 
										   ($commission->status === 'approved' ? 'bg-info-focus text-info-main' : 
										   ($commission->status === 'pending' ? 'bg-warning-focus text-warning-main' : 'bg-danger-focus text-danger-main')) }}">
										{{ ucfirst($commission->status) }}
									</span>
								</td>
								<td>{{ $commission->created_at->format('M d, Y') }}</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										@if($commission->status === 'pending')
											<form action="{{ route('admin.affiliates.commissions.approve', $commission) }}" method="POST" class="d-inline">
												@csrf
												<button type="submit" class="btn btn-sm btn-success radius-12">Approve</button>
											</form>
											<form action="{{ route('admin.affiliates.commissions.cancel', $commission) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this commission?')">
												@csrf
												<button type="submit" class="btn btn-sm btn-danger radius-12">Cancel</button>
											</form>
										@endif
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No commissions" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No commissions found</h6>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($commissions->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $commissions->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

