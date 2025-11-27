@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Customer Details - {{ $user->name }}</h6>
			<p class="text-secondary-light mb-0">View complete customer information, orders, and activity.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.users.index', ['tab' => 'customers']) }}" class="hover-text-primary">Customers</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Details</li>
		</ul>
	</div>

	<div class="row g-4">
		<div class="col-lg-3">
			<div class="card border-0">
				<div class="card-body p-24 text-center">
					<x-avatar :user="$user" size="xl" class="w-120-px h-120-px mb-3" />
					<h6 class="fw-semibold mb-1">{{ $user->name }}</h6>
					<p class="text-secondary-light mb-3">{{ $user->email }}</p>
					@if($user->phone)
						<p class="text-secondary-light mb-3">{{ $user->phone }}</p>
					@endif
					@if($user->email_verified_at)
						<span class="badge bg-success mb-3">Verified</span>
					@else
						<span class="badge bg-warning mb-3">Not Verified</span>
					@endif
					<div class="d-grid gap-2 mt-4">
						<a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary radius-12">Edit Customer</a>
					</div>
				</div>
			</div>

			<div class="card border-0 mt-4">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Quick Stats</h6>
					<div class="d-flex flex-column gap-3">
						<div>
							<label class="text-secondary-light small">Total Orders</label>
							<div class="fw-semibold">{{ $user->orders->count() }}</div>
						</div>
						<div>
							<label class="text-secondary-light small">Total Spent</label>
							<div class="fw-semibold">{{ currency($user->orders->sum('total_amount')) }}</div>
						</div>
						<div>
							<label class="text-secondary-light small">Support Tickets</label>
							<div class="fw-semibold">{{ $user->supportTickets->count() }}</div>
						</div>
						<div>
							<label class="text-secondary-light small">Member Since</label>
							<div class="fw-semibold">{{ $user->created_at->format('d M Y') }}</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-9">
			<div class="card border-0">
				<div class="card-body p-24">
					<ul class="nav nav-tabs border-0 mb-24" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
								Overview
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
								Orders ({{ $user->orders->count() }})
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
								Activity Logs
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="addresses-tab" data-bs-toggle="tab" data-bs-target="#addresses" type="button" role="tab">
								Addresses ({{ $user->addresses->count() }})
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="tickets-tab" data-bs-toggle="tab" data-bs-target="#tickets" type="button" role="tab">
								Support Tickets ({{ $user->supportTickets->count() }})
							</button>
						</li>
					</ul>

					<div class="tab-content" id="customer-tabs-content">
						<div class="tab-pane fade show active" id="overview" role="tabpanel">
							<div class="row g-4">
								<div class="col-md-6">
									<label class="text-secondary-light small">Full Name</label>
									<div class="fw-medium">{{ $user->name }}</div>
								</div>
								<div class="col-md-6">
									<label class="text-secondary-light small">Email</label>
									<div class="fw-medium">{{ $user->email }}</div>
								</div>
								<div class="col-md-6">
									<label class="text-secondary-light small">Phone</label>
									<div class="fw-medium">{{ $user->phone ?? '-' }}</div>
								</div>
								<div class="col-md-6">
									<label class="text-secondary-light small">Email Verified</label>
									<div>
										@if($user->email_verified_at)
											<span class="badge bg-success">Yes - {{ $user->email_verified_at->format('d M Y') }}</span>
										@else
											<span class="badge bg-warning">No</span>
										@endif
									</div>
								</div>
								<div class="col-md-6">
									<label class="text-secondary-light small">Registered</label>
									<div class="fw-medium">{{ $user->created_at->format('d M Y H:i') }}</div>
								</div>
								<div class="col-md-6">
									<label class="text-secondary-light small">Last Login</label>
									<div class="fw-medium">
										@php
											$lastLogin = $user->activityLogs()->where('action_type', 'login')->latest()->first();
										@endphp
										{{ $lastLogin ? $lastLogin->created_at->format('d M Y H:i') : 'Never' }}
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="orders" role="tabpanel">
							@if($user->orders->count() > 0)
								<div class="table-responsive">
									<table class="table bordered-table mb-0">
										<thead>
											<tr>
												<th>Order #</th>
												<th>Status</th>
												<th>Total</th>
												<th>Date</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											@foreach($user->orders as $order)
												<tr>
													<td>#{{ $order->order_number ?? $order->id }}</td>
													<td>
														<span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
															{{ ucfirst($order->status) }}
														</span>
													</td>
													<td>{{ currency($order->total_amount) }}</td>
													<td>{{ $order->created_at->format('d M Y H:i') }}</td>
													<td class="text-end">
														<a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<p class="text-center py-4 text-secondary-light">No orders found.</p>
							@endif
						</div>

						<div class="tab-pane fade" id="activity" role="tabpanel">
							<a href="{{ route('admin.users.customer-activity', $user) }}" class="btn btn-outline-primary mb-3">View All Activities</a>
							<div class="table-responsive">
								<table class="table bordered-table mb-0">
									<thead>
										<tr>
											<th>Action</th>
											<th>Description</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
										@forelse($user->activityLogs->take(10) as $activity)
											<tr>
												<td>
													<span class="badge bg-secondary">{{ ucfirst($activity->action_type) }}</span>
												</td>
												<td>{{ $activity->description }}</td>
												<td>{{ $activity->created_at->format('d M Y H:i') }}</td>
											</tr>
										@empty
											<tr>
												<td colspan="3" class="text-center py-4 text-secondary-light">No activities found.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>
						</div>

						<div class="tab-pane fade" id="addresses" role="tabpanel">
							@if($user->addresses->count() > 0)
								<div class="row g-3">
									@foreach($user->addresses as $address)
										<div class="col-md-6">
											<div class="card border p-3">
												<div class="d-flex justify-content-between align-items-start mb-2">
													<h6 class="fw-semibold mb-0">{{ $address->label ?? 'Address' }}</h6>
													@if(isset($address->is_default) && $address->is_default)
														<span class="badge bg-primary">Default</span>
													@endif
												</div>
												<p class="mb-1">{{ $address->contact_name ?? $user->name }}</p>
												<p class="mb-1 text-secondary-light small">{{ $address->street ?? '-' }}</p>
												<p class="mb-1 text-secondary-light small">{{ ($address->city ?? '') . ', ' . ($address->state ?? '') . ' ' . ($address->postal_code ?? '') }}</p>
												@if(isset($address->country))
													<p class="mb-0 text-secondary-light small">{{ $address->country }}</p>
												@endif
												@if(isset($address->contact_phone) && $address->contact_phone)
													<p class="mb-0 text-secondary-light small">Phone: {{ $address->contact_phone }}</p>
												@endif
											</div>
										</div>
									@endforeach
								</div>
							@else
								<p class="text-center py-4 text-secondary-light">No addresses found.</p>
							@endif
						</div>

						<div class="tab-pane fade" id="tickets" role="tabpanel">
							@if($user->supportTickets->count() > 0)
								<div class="table-responsive">
									<table class="table bordered-table mb-0">
										<thead>
											<tr>
												<th>Ticket #</th>
												<th>Subject</th>
												<th>Status</th>
												<th>Priority</th>
												<th>Created</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											@foreach($user->supportTickets as $ticket)
												<tr>
													<td>{{ $ticket->ticket_number }}</td>
													<td>{{ Str::limit($ticket->subject, 50) }}</td>
													<td>
														<span class="badge bg-{{ $ticket->status === 'closed' ? 'secondary' : ($ticket->status === 'resolved' ? 'success' : 'warning') }}">
															{{ ucfirst($ticket->status) }}
														</span>
													</td>
													<td>
														<span class="badge bg-{{ $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'info') }}">
															{{ ucfirst($ticket->priority) }}
														</span>
													</td>
													<td>{{ $ticket->created_at->format('d M Y H:i') }}</td>
													<td class="text-end">
														<a href="{{ route('admin.support.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">View</a>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<p class="text-center py-4 text-secondary-light">No support tickets found.</p>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

