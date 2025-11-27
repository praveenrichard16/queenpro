@extends('layouts.customer')

@section('title', 'My Orders')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Orders &amp; Tracking</h6>
			<p class="text-secondary-light mb-0">Review your recent purchases, check delivery status, and download invoices.</p>
		</div>
		<form method="GET" class="d-flex gap-2">
			<input type="text" name="search" class="form-control radius-12 bg-neutral-50" value="{{ request('search') }}" placeholder="Search by order or status">
			<button class="btn btn-primary radius-12 px-20">Search</button>
		</form>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			@if($orders->isEmpty())
				<div class="text-center py-40 text-secondary-light">
					You don’t have any orders yet. <a href="{{ route('products.index') }}" class="text-primary-light fw-semibold">Shop now</a>
				</div>
			@else
				<div class="table-responsive">
					<table class="table align-middle mb-0">
						<thead>
							<tr>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Order</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Placed On</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Items</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Total</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Status</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($orders as $order)
							<tr>
								<td class="fw-semibold text-sm">{{ $order->order_number }}</td>
								<td class="text-sm text-secondary-light">{{ $order->created_at?->format('d M Y') }}</td>
								<td class="text-sm text-secondary-light">{{ $order->order_items_count }}</td>
								<td class="text-sm fw-semibold">{{ \App\Services\CurrencyService::format($order->total_amount) }}</td>
								<td>
									<span class="badge radius-8 text-xs fw-semibold
										@if($order->status === 'delivered') bg-success-50 text-success
										@elseif($order->status === 'in_transit') bg-info-50 text-info-main
										@elseif($order->status === 'pending') bg-warning-50 text-warning
										@else bg-neutral-100 text-secondary-light @endif">
										{{ ucfirst(str_replace('_',' ', $order->status)) }}
									</span>
								</td>
								<td class="text-end">
									<a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary radius-12 px-16">View</a>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="mt-24">
					{{ $orders->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

