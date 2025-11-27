@extends('layouts.admin')

@section('title', 'Customer Activity Logs')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Activity Logs - {{ $user->name }}</h6>
			<p class="text-secondary-light mb-0">View all activities and journey events for this customer.</p>
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
			<li class="fw-medium text-secondary-light">Activity Logs</li>
		</ul>
	</div>

	<div class="row g-4">
		<div class="col-lg-6">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">System Activities</h6>
					<div class="table-responsive">
						<table class="table bordered-table mb-0 align-middle">
							<thead>
								<tr>
									<th>Action</th>
									<th>Description</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								@forelse($activities as $activity)
									<tr>
										<td>
											<span class="badge bg-secondary">{{ ucfirst($activity->action_type) }}</span>
										</td>
										<td>{{ $activity->description }}</td>
										<td>{{ $activity->created_at->format('d M Y H:i') }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="3" class="text-center py-4 text-secondary-light">No system activities found.</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@if($activities->hasPages())
						<div class="mt-3">
							{{ $activities->links() }}
						</div>
					@endif
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Customer Journey Events</h6>
					<div class="table-responsive">
						<table class="table bordered-table mb-0 align-middle">
							<thead>
								<tr>
									<th>Event Type</th>
									<th>Category</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								@forelse($journeyEvents as $event)
									<tr>
										<td>
											<span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</span>
										</td>
										<td>{{ ucfirst($event->event_category) }}</td>
										<td>{{ $event->created_at->format('d M Y H:i') }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="3" class="text-center py-4 text-secondary-light">No journey events found.</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					@if($journeyEvents->hasPages())
						<div class="mt-3">
							{{ $journeyEvents->links() }}
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

