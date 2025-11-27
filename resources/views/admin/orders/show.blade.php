@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Order #{{ $order->order_number ?? $order->id }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.orders.index') }}" class="hover-text-primary">Orders</a>
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

	<div class="row g-3">
		<div class="col-xxl-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
						<div>
							<h6 class="mb-6">Order Summary</h6>
							<p class="text-sm text-secondary-light mb-0">Placed on {{ $order->created_at?->format('d M Y, h:i A') }}</p>
						</div>
						<span class="px-20 py-6 rounded-pill fw-semibold text-sm text-capitalize {{ match($order->status) {
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
					</div>

					<div class="table-responsive mt-24">
						<table class="table mb-0 align-middle">
							<thead class="table-light">
								<tr>
									<th>Product</th>
									<th class="text-end">Quantity</th>
									<th class="text-end">Unit Price</th>
									<th class="text-end">Total</th>
								</tr>
							</thead>
							<tbody>
								@foreach($order->orderItems as $item)
									<tr>
										<td>
											<h6 class="text-md mb-4 fw-medium">{{ $item->product->name ?? $item->product_name }}</h6>
											<p class="text-sm text-secondary-light mb-0">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
										</td>
										<td class="text-end">{{ $item->quantity }}</td>
										<td class="text-end">{{ \App\Services\CurrencyService::format($item->unit_price ?? $item->price) }}</td>
										<td class="text-end">{{ \App\Services\CurrencyService::format(($item->unit_price ?? $item->price) * $item->quantity) }}</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<th colspan="3" class="text-end">Total Amount</th>
									<th class="text-end">{{ $order->formatted_total }}</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>

			@if($order->notes)
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h6 class="mb-12">Order Notes</h6>
						<p class="mb-0 text-secondary-light">{{ $order->notes }}</p>
					</div>
				</div>
			@endif
		</div>

		<div class="col-xxl-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="mb-16">Update Status</h6>
					<form method="POST" action="{{ route('admin.orders.update', $order) }}" class="d-grid gap-3">
						@csrf
						@method('PUT')
						<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px">
							@foreach(['pending','processing','shipped','delivered','cancelled'] as $status)
								<option value="{{ $status }}" @selected($order->status === $status || ($status === 'delivered' && $order->status === 'completed'))>{{ ucfirst($status) }}</option>
							@endforeach
						</select>
						<textarea name="notes" rows="3" class="form-control bg-neutral-50 radius-12" placeholder="Optional notes">{{ old('notes', $order->notes) }}</textarea>
						<button class="btn btn-primary radius-12">Save changes</button>
					</form>
				</div>
			</div>

			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="mb-16">Customer</h6>
					<p class="fw-semibold mb-4">{{ $order->customer_name ?? 'Guest Customer' }}</p>
					<p class="text-sm text-secondary-light mb-1">{{ $order->customer_email }}</p>
					@if($order->customer_phone)
						<p class="text-sm text-secondary-light">{{ $order->customer_phone }}</p>
					@endif
				</div>
			</div>

			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="mb-16">Addresses</h6>
					<div class="mb-16">
						<h6 class="text-sm text-secondary-light mb-6">Shipping</h6>
						<p class="mb-0">{!! nl2br(e(is_array($order->shipping_address) ? implode("\n", $order->shipping_address) : ($order->shipping_address ?? 'N/A'))) !!}</p>
					</div>
					<div>
						<h6 class="text-sm text-secondary-light mb-6">Billing</h6>
						<p class="mb-0">{!! nl2br(e(is_array($order->billing_address) ? implode("\n", $order->billing_address) : ($order->billing_address ?? 'Same as shipping'))) !!}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
