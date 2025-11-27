@extends('layouts.admin')

@section('title', 'Offers')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Offers</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Offers</li>
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
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="{{ request('search') }}" placeholder="Coupon code">
					</div>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Offer Type</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="offer_type">
						<option value="">All Types</option>
						<option value="common" @selected(request('offer_type') === 'common')>Common</option>
						<option value="product" @selected(request('offer_type') === 'product')>Product</option>
						<option value="category" @selected(request('offer_type') === 'category')>Category</option>
						<option value="brand" @selected(request('offer_type') === 'brand')>Brand</option>
						<option value="user" @selected(request('offer_type') === 'user')>User</option>
						<option value="billing_amount" @selected(request('offer_type') === 'billing_amount')>Billing Amount</option>
					</select>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All Statuses</option>
						<option value="active" @selected(request('status') === 'active')>Active</option>
						<option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
					</select>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">User Segment</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="user_segment">
						<option value="">All Segments</option>
						<option value="first_time_buyers" @selected(request('user_segment') === 'first_time_buyers')>First Time Buyers</option>
						<option value="repeat_customers" @selected(request('user_segment') === 'repeat_customers')>Repeat Customers</option>
						<option value="minimum_purchase" @selected(request('user_segment') === 'minimum_purchase')>Minimum Purchase</option>
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-lg-12">
					<a href="{{ route('admin.offers.create') }}" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Create Offer
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
							<th>Code</th>
							<th>Type</th>
							<th>Offer Type</th>
							<th>Value</th>
							<th>Valid Period</th>
							<th>Usage</th>
							<th>Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($offers as $offer)
							<tr>
								<td>
									<h6 class="text-md mb-2 fw-medium">{{ $offer->code }}</h6>
									@if($offer->description)
										<p class="text-sm text-secondary-light mb-0">{{ Str::limit($offer->description, 50) }}</p>
									@endif
								</td>
								<td>
									<span class="px-12 py-4 rounded-pill fw-semibold text-xs {{ $offer->type === 'percentage' ? 'bg-info-focus text-info-main' : 'bg-primary-focus text-primary-main' }}">
										{{ ucfirst($offer->type) }}
									</span>
								</td>
								<td>
									<span class="px-12 py-4 rounded-pill fw-semibold text-xs bg-warning-focus text-warning-main">
										{{ ucfirst(str_replace('_', ' ', $offer->offer_type ?? 'common')) }}
									</span>
									@if($offer->user_segment)
										<div class="mt-1">
											<span class="text-xs text-secondary-light">{{ ucfirst(str_replace('_', ' ', $offer->user_segment)) }}</span>
										</div>
									@endif
								</td>
								<td>
									@if($offer->type === 'percentage')
										{{ $offer->value }}%
									@else
										{{ \App\Services\CurrencyService::format($offer->value) }}
									@endif
									@if($offer->min_amount)
										<div class="text-xs text-secondary-light">Min: {{ \App\Services\CurrencyService::format($offer->min_amount) }}</div>
									@endif
								</td>
								<td>
									@if($offer->valid_from || $offer->valid_until)
										<div class="text-sm">
											@if($offer->valid_from)
												<div>From: {{ $offer->valid_from->format('M d, Y') }}</div>
											@endif
											@if($offer->valid_until)
												<div>Until: {{ $offer->valid_until->format('M d, Y') }}</div>
											@endif
										</div>
									@else
										<span class="text-secondary-light">—</span>
									@endif
								</td>
								<td>
									<div class="text-sm">
										{{ $offer->used_count ?? 0 }} / {{ $offer->usage_limit ?? '∞' }}
									</div>
									@if($offer->per_user_limit)
										<div class="text-xs text-secondary-light">Per user: {{ $offer->per_user_limit }}</div>
									@endif
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $offer->status === 'active' ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
										{{ ucfirst($offer->status) }}
									</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.offers.edit', $offer) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" onsubmit="return confirm('Delete this offer?')" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="8" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No offers" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No offers found</h6>
									<p class="text-secondary-light mb-0">Create your first offer to attract customers.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($offers->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $offers->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

