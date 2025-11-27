@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Orders</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Orders</li>
		</ul>
	</div>

	<div class="row g-3 mb-24">
		<div class="col-md-3 col-sm-6">
			<div class="card border-0 bg-gradient-start-1 h-100">
				<div class="card-body p-20">
					<p class="text-sm fw-medium text-primary-light mb-6">Total Orders</p>
					<h5 class="mb-0">{{ number_format($orders->total()) }}</h5>
				</div>
			</div>
		</div>
		@foreach(['pending' => 'warning', 'processing' => 'info', 'completed' => 'success', 'cancelled' => 'danger'] as $status => $tone)
			<div class="col-md-3 col-sm-6">
				<div class="card border-0 h-100 bg-{{ $tone }}-focus">
					<div class="card-body p-20">
						<p class="text-sm fw-medium text-secondary-light mb-6 text-capitalize">{{ $status }}</p>
						<h5 class="mb-0">{{ $statusCounts[$status] ?? 0 }}</h5>
					</div>
				</div>
			</div>
		@endforeach
	</div>

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="{{ request('search') }}" placeholder="Order #, customer, email">
					</div>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All statuses</option>
						@foreach(['pending','processing','completed','cancelled'] as $status)
							<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Date</label>
					<input type="date" class="form-control h-56-px bg-neutral-50 radius-12" name="date" value="{{ request('date') }}">
				</div>
				<div class="col-lg-2 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
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
							<th>Order</th>
							<th>Customer</th>
							<th class="text-end">Total</th>
							<th>Status</th>
							<th class="text-end">Placed</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($orders as $order)
							<tr>
								<td>
									<a href="{{ route('admin.orders.show', $order) }}" class="text-primary-600 fw-semibold">#{{ $order->order_number ?? $order->id }}</a>
									<p class="text-sm text-secondary-light mb-0">{{ $order->payment_method ?? 'N/A' }}</p>
								</td>
								<td>
									<h6 class="text-md mb-4 fw-medium">{{ $order->customer_name ?? 'Guest Customer' }}</h6>
									<p class="text-sm text-secondary-light mb-0">{{ $order->customer_email }}</p>
								</td>
								<td class="text-end fw-semibold">{{ $order->formatted_total }}</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-neutral-200 text-capitalize {{ match($order->status) {
											'delivered' => 'bg-success-focus text-success-main',
											'completed' => 'bg-success-focus text-success-main',
											'pending' => 'bg-warning-focus text-warning-main',
											'processing' => 'bg-info-focus text-info-main',
											'shipped' => 'bg-primary-focus text-primary-main',
											'cancelled' => 'bg-danger-focus text-danger-main',
											default => 'bg-neutral-200 text-neutral-600'
										} }}">
										{{ ucfirst($order->status === 'completed' ? 'delivered' : ($order->status ?? 'pending')) }}
									</span>
								</td>
								<td class="text-end text-secondary-light">{{ $order->created_at?->format('d M Y, h:i A') }}</td>
								<td class="text-end">
									<a href="{{ route('admin.orders.show', $order) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
										<iconify-icon icon="iconamoon:eye"></iconify-icon>
									</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No orders" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No orders found</h6>
									<p class="text-secondary-light mb-0">Adjust your filters or check back later.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($orders->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $orders->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection
