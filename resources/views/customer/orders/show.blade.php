@extends('layouts.customer')

@section('title', 'Order '.$order->order_number)

@section('content')
@php
	$statusSteps = [
		'pending' => ['label' => 'Pending', 'icon' => 'solar:clock-square-outline'],
		'processing' => ['label' => 'Processing', 'icon' => 'solar:settings-outline'],
		'in_transit' => ['label' => 'In Transit', 'icon' => 'solar:rocket-2-outline'],
		'delivered' => ['label' => 'Delivered', 'icon' => 'solar:check-square-outline'],
	];

	$currentStatus = $order->status;
	$reached = true;
@endphp

<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Order {{ $order->order_number }}</h6>
			<p class="text-secondary-light mb-0">Placed on {{ $order->created_at?->format('d M Y, H:i') }}</p>
		</div>
		<a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
			Back to orders
		</a>
	</div>

	<div class="card border-0 shadow-sm mb-24">
		<div class="card-body p-24">
			<h6 class="fw-semibold mb-16">Delivery timeline</h6>
			<div class="timeline d-flex flex-wrap gap-4">
				@foreach($statusSteps as $key => $step)
					@php
						if ($reached && $key === $currentStatus) {
							$state = 'current';
							$reached = false;
						} elseif ($reached) {
							$state = 'done';
						} else {
							$state = 'upcoming';
						}
					@endphp
					<div class="d-flex align-items-center gap-3 px-16 py-12 radius-16 border {{ $state !== 'upcoming' ? 'bg-neutral-50' : '' }}">
						<span class="w-40-px h-40-px rounded-circle d-flex align-items-center justify-content-center
							{{ $state === 'done' ? 'bg-success-50 text-success' : ($state === 'current' ? 'bg-primary-50 text-primary-600' : 'bg-neutral-100 text-secondary-light') }}">
							<iconify-icon icon="{{ $step['icon'] }}" class="text-lg"></iconify-icon>
						</span>
						<div>
							<div class="fw-semibold text-sm">{{ $step['label'] }}</div>
							@if($state === 'current')
								<div class="text-xs text-primary-600">Currently here</div>
							@elseif($state === 'done')
								<div class="text-xs text-secondary-light">Completed</div>
							@else
								<div class="text-xs text-secondary-light">Pending</div>
							@endif
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-xxl-8">
			<div class="card border-0 shadow-sm">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Items</h6>
					@if($order->orderItems->isEmpty())
						<p class="text-secondary-light mb-0">No line items recorded for this order.</p>
					@else
						<div class="table-responsive">
							<table class="table align-middle mb-0">
								<thead>
									<tr>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold">Product</th>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold">Quantity</th>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold">Price</th>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold text-end">Total</th>
									</tr>
								</thead>
								<tbody>
								@foreach($order->orderItems as $item)
									<tr>
										<td class="fw-medium text-sm">{{ $item->product->name ?? 'Product #'.$item->product_id }}</td>
										<td class="text-sm text-secondary-light">{{ $item->quantity }}</td>
										<td class="text-sm">{{ \App\Services\CurrencyService::format($item->price) }}</td>
										<td class="text-end text-sm fw-semibold">{{ \App\Services\CurrencyService::format($item->quantity * $item->price) }}</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					@endif
				</div>
			</div>
		</div>
		<div class="col-xxl-4">
			<div class="card border-0 shadow-sm mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Order Summary</h6>
					<ul class="list-unstyled d-grid gap-12 mb-0">
						<li class="d-flex justify-content-between text-sm">
							<span class="text-secondary-light">Status</span>
							<span class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $order->status) }}</span>
						</li>
						<li class="d-flex justify-content-between text-sm">
							<span class="text-secondary-light">Payment</span>
							<span class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
						</li>
						<li class="d-flex justify-content-between text-sm">
							<span class="text-secondary-light">Total Amount</span>
							<span class="fw-semibold">{{ \App\Services\CurrencyService::format($order->total_amount) }}</span>
						</li>
					</ul>
				</div>
			</div>

			<div class="card border-0 shadow-sm mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Shipping Address</h6>
					@if(is_array($order->shipping_address))
						<p class="mb-0 text-sm text-secondary">
							{{ $order->shipping_address['street'] ?? '' }}<br>
							{{ $order->shipping_address['city'] ?? '' }}@if(!empty($order->shipping_address['state'])), {{ $order->shipping_address['state'] }} @endif {{ $order->shipping_address['zip'] ?? '' }}<br>
							{{ $order->shipping_address['country'] ?? '' }}
						</p>
					@else
						<p class="mb-0 text-secondary-light text-sm">Address information unavailable.</p>
					@endif
				</div>
			</div>

			<div class="card border-0 shadow-sm">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Billing Address</h6>
					@if(is_array($order->billing_address))
						<p class="mb-0 text-sm text-secondary">
							{{ $order->billing_address['street'] ?? '' }}<br>
							{{ $order->billing_address['city'] ?? '' }}@if(!empty($order->billing_address['state'])), {{ $order->billing_address['state'] }} @endif {{ $order->billing_address['zip'] ?? '' }}<br>
							{{ $order->billing_address['country'] ?? '' }}
						</p>
					@else
						<p class="mb-0 text-secondary-light text-sm">Address information unavailable.</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

