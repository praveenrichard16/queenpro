@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $customer->name }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.customers.index') }}" class="hover-text-primary">Customers</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Details</li>
		</ul>
	</div>

	<div class="row g-4">
		<div class="col-lg-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24 text-center">
					<div class="w-80-px h-80-px rounded-circle bg-primary-focus mx-auto mb-16 d-flex align-items-center justify-content-center">
						<span class="text-primary-main fw-bold text-xl">{{ substr($customer->name, 0, 1) }}</span>
					</div>
					<h5 class="fw-semibold mb-8">{{ $customer->name }}</h5>
					<p class="text-secondary-light mb-16">{{ $customer->email }}</p>
					@if($customer->phone)
						<p class="text-sm mb-16"><iconify-icon icon="solar:phone-outline"></iconify-icon> {{ $customer->phone }}</p>
					@endif
					<a href="{{ route('admin.customers.journey', $customer) }}" class="btn btn-primary w-100 radius-12">
						<iconify-icon icon="solar:route-outline"></iconify-icon> View Journey
					</a>
				</div>
			</div>

			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Statistics</h6>
					<div class="d-flex flex-column gap-3">
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-secondary-light">Total Orders</span>
							<span class="fw-semibold">{{ $stats['total_orders'] }}</span>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-secondary-light">Total Spent</span>
							<span class="fw-semibold">{{ \App\Services\CurrencyService::format($stats['total_spent']) }}</span>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-secondary-light">Average Order</span>
							<span class="fw-semibold">{{ \App\Services\CurrencyService::format($stats['average_order_value']) }}</span>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-secondary-light">Cart Abandonments</span>
							<span class="fw-semibold">{{ $stats['cart_abandonments'] }}</span>
						</div>
						@if($stats['last_order_date'])
							<div class="d-flex justify-content-between align-items-center">
								<span class="text-secondary-light">Last Order</span>
								<span class="fw-semibold">{{ $stats['last_order_date']->format('M d, Y') }}</span>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Recent Orders</h6>
					@if($customer->orders->count() > 0)
						<div class="table-responsive">
							<table class="table table-sm">
								<thead>
									<tr>
										<th>Order #</th>
										<th>Date</th>
										<th>Status</th>
										<th class="text-end">Total</th>
									</tr>
								</thead>
								<tbody>
									@foreach($customer->orders->take(10) as $order)
										<tr>
											<td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
											<td>{{ $order->created_at->format('M d, Y') }}</td>
											<td><span class="badge bg-{{ $order->status === 'delivered' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span></td>
											<td class="text-end">{{ \App\Services\CurrencyService::format($order->total_amount) }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@else
						<p class="text-secondary-light mb-0">No orders yet.</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

