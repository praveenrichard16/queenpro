@extends('layouts.customer')

@section('title', 'Account Overview')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<p class="text-secondary-light mb-4">Welcome back, {{ auth()->user()->name }} 👋</p>
			<h4 class="fw-semibold mb-0">Here’s what’s happening with your account today</h4>
		</div>
		<div class="d-flex flex-wrap gap-3">
			<a href="{{ route('customer.support.tickets.create') }}" class="btn btn-primary radius-12 px-24 d-flex align-items-center gap-2">
				<iconify-icon icon="solar:lifebuoy-outline" class="text-lg"></iconify-icon>
				New Support Ticket
			</a>
			<a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
				<iconify-icon icon="solar:bag-check-outline" class="text-lg"></iconify-icon>
				View Orders
			</a>
		</div>
	</div>

	<div class="row g-3 mb-24">
		<div class="col-xl-3 col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-20">
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-secondary-light text-sm">Total Orders</span>
						<iconify-icon icon="solar:bag-smile-outline" class="text-2xl text-primary-light"></iconify-icon>
					</div>
					<h3 class="mt-16 mb-0">{{ $ordersCount }}</h3>
					<p class="text-secondary-light text-xs mb-0">All purchases made using this account</p>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-20">
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-secondary-light text-sm">In Transit</span>
						<iconify-icon icon="solar:rocket-2-outline" class="text-2xl text-info-main"></iconify-icon>
					</div>
					<h3 class="mt-16 mb-0">{{ $inTransitCount }}</h3>
					<p class="text-secondary-light text-xs mb-0">Orders currently on the way</p>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-20">
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-secondary-light text-sm">Delivered</span>
						<iconify-icon icon="solar:check-square-outline" class="text-2xl text-success"></iconify-icon>
					</div>
					<h3 class="mt-16 mb-0">{{ $deliveredCount }}</h3>
					<p class="text-secondary-light text-xs mb-0">Completed orders this account has received</p>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-20">
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-secondary-light text-sm">Open Tickets</span>
						<iconify-icon icon="solar:chat-dots-outline" class="text-2xl text-warning"></iconify-icon>
					</div>
					<h3 class="mt-16 mb-0">{{ $openTicketsCount }}</h3>
					<p class="text-secondary-light text-xs mb-0">Support tickets awaiting attention</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-xxl-7">
			<div class="card border-0 h-100 shadow-sm">
				<div class="card-body p-24">
					<div class="d-flex justify-content-between align-items-center mb-20">
						<h6 class="fw-semibold mb-0">Recent Orders</h6>
						<a href="{{ route('customer.orders.index') }}" class="text-sm fw-semibold text-primary-light">See all</a>
					</div>
					@if($recentOrders->isEmpty())
						<div class="text-center py-32 text-secondary-light">
							No orders yet. <a href="{{ route('products.index') }}" class="text-primary-light fw-semibold">Start shopping</a>.
						</div>
					@else
						<div class="table-responsive">
							<table class="table align-middle mb-0">
								<thead>
									<tr>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold">Order</th>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold">Placed On</th>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold">Total</th>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold">Status</th>
										<th class="text-secondary-light text-xs text-uppercase fw-semibold text-end">Actions</th>
									</tr>
								</thead>
								<tbody>
								@foreach($recentOrders as $order)
									<tr>
										<td class="fw-semibold text-sm">{{ $order->order_number }}</td>
										<td class="text-sm text-secondary-light">{{ $order->created_at?->format('d M Y') }}</td>
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
					@endif
				</div>
			</div>
		</div>
		<div class="col-xxl-5">
			<div class="card border-0 h-100 shadow-sm">
				<div class="card-body p-24">
					<div class="d-flex justify-content-between align-items-center mb-20">
						<h6 class="fw-semibold mb-0">Latest Support Updates</h6>
						<a href="{{ route('customer.support.tickets.index') }}" class="text-sm fw-semibold text-primary-light">View tickets</a>
					</div>
					@if($recentTickets->isEmpty())
						<div class="text-center py-32 text-secondary-light">
							Need assistance? <a href="{{ route('customer.support.tickets.create') }}" class="text-primary-light fw-semibold">Open a ticket</a>.
						</div>
					@else
						<ul class="list-unstyled d-grid gap-16 mb-0">
							@foreach($recentTickets as $ticket)
								<li class="p-16 radius-16 border bg-neutral-50">
									<div class="d-flex justify-content-between align-items-start gap-2">
										<div>
											<div class="fw-semibold text-sm">{{ $ticket->ticket_number }}</div>
											<div class="text-xs text-secondary-light">{{ $ticket->subject }}</div>
										</div>
										<span class="badge radius-8 text-xxs fw-semibold
											@if($ticket->status->value === 'open') bg-warning-50 text-warning
											@elseif($ticket->status->value === 'in_progress') bg-info-50 text-info-main
											@elseif($ticket->status->value === 'awaiting_customer') bg-primary-50 text-primary-600
											@elseif($ticket->status->value === 'resolved') bg-success-50 text-success
											@else bg-neutral-100 text-secondary-light @endif">
											{{ $ticket->status->label() }}
										</span>
									</div>
									<div class="mt-12 text-xs text-secondary-light d-flex justify-content-between">
										<span>Updated {{ $ticket->updated_at?->diffForHumans() }}</span>
										<a href="{{ route('customer.support.tickets.show', $ticket) }}" class="fw-semibold text-primary-light">View</a>
									</div>
								</li>
							@endforeach
						</ul>
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="row g-4 mb-24">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-body p-24">
					<div class="d-flex justify-content-between align-items-center mb-20">
						<h6 class="fw-semibold mb-0">Recent Notifications</h6>
						<a href="{{ route('notifications.index') }}" class="text-sm fw-semibold text-primary-light">View all</a>
					</div>
					@if($recentNotifications->isEmpty())
						<div class="text-center py-32 text-secondary-light">
							<iconify-icon icon="solar:bell-off-outline" class="text-3xl mb-2"></iconify-icon>
							<p class="mb-0">No notifications</p>
						</div>
					@else
						<div class="list-group list-group-flush">
							@foreach($recentNotifications as $notification)
								@php
									$data = $notification->data;
									$isRead = $notification->read_at !== null;
									$iconColor = $data['icon_color'] ?? 'primary';
									$iconBgClass = "bg-{$iconColor}-focus";
									$iconTextClass = "text-{$iconColor}-main";
								@endphp
								<a href="{{ $data['url'] ?? '#' }}" class="list-group-item list-group-item-action border-0 px-0 py-12 {{ !$isRead ? 'bg-primary-50' : '' }}">
									<div class="d-flex align-items-start gap-3">
										<span class="w-40-px h-40-px rounded-circle flex-shrink-0 {{ $iconBgClass }} d-flex justify-content-center align-items-center">
											<iconify-icon icon="{{ $data['icon'] ?? 'solar:bell-outline' }}" class="{{ $iconTextClass }} text-lg"></iconify-icon>
										</span>
										<div class="flex-grow-1">
											<h6 class="text-sm fw-semibold mb-1">{{ $data['title'] ?? 'Notification' }}</h6>
											<p class="text-sm text-secondary-light mb-1">{{ $data['message'] ?? '' }}</p>
											<p class="text-xs text-secondary-light mb-0">{{ $notification->created_at->diffForHumans() }}</p>
										</div>
									</div>
								</a>
							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
