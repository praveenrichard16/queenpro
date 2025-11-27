@extends('layouts.admin')

@section('title', 'Staff Activity Logs')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Activity Logs - {{ $user->name }}</h6>
			<p class="text-secondary-light mb-0">View all activities performed by this staff member.</p>
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
				<a href="{{ route('admin.users.index', ['tab' => 'staff']) }}" class="hover-text-primary">Staff</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Activity Logs</li>
		</ul>
	</div>

	<div class="card border-0 mb-24">
		<div class="card-body p-24">
			<div class="d-flex align-items-center gap-3 mb-4">
				<x-avatar :user="$user" size="lg" class="w-64-px h-64-px" />
				<div>
					<h6 class="fw-semibold mb-1">{{ $user->name }}</h6>
					<p class="text-secondary-light mb-0">{{ $user->email }}</p>
					@if($user->designation)
						<span class="badge bg-primary">{{ $user->designation }}</span>
					@endif
				</div>
			</div>
			@php
				$user->load('modules');
			@endphp
			@if($user->modules->count() > 0)
				<div class="mt-3">
					<label class="text-secondary-light small">Assigned Modules:</label>
					<div class="d-flex flex-wrap gap-2 mt-2">
						@foreach($user->modules as $module)
							<span class="badge bg-info">{{ config("modules.modules.{$module->module_name}.name", $module->module_name) }}</span>
						@endforeach
					</div>
				</div>
			@endif
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Action</th>
							<th>Description</th>
							<th>Route</th>
							<th>IP Address</th>
							<th>Date & Time</th>
						</tr>
					</thead>
					<tbody>
						@forelse($activities as $activity)
							<tr>
								<td>
									<span class="badge bg-{{ $activity->action_type === 'create' ? 'success' : ($activity->action_type === 'update' ? 'info' : ($activity->action_type === 'delete' ? 'danger' : 'secondary')) }}">
										{{ ucfirst($activity->action_type) }}
									</span>
								</td>
								<td>{{ $activity->description }}</td>
								<td>
									<code class="text-sm">{{ $activity->route ?? '-' }}</code>
								</td>
								<td>{{ $activity->ip_address ?? '-' }}</td>
								<td>{{ $activity->created_at->format('d M Y H:i:s') }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="5" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No activities" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No activities found</h6>
									<p class="text-secondary-light mb-0">This staff member has no recorded activities yet.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($activities->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $activities->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

