@extends('layouts.admin')

@section('title', 'Lead Activity Log')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Activity Log: {{ $lead->name }}</h6>
		<a href="{{ route('admin.leads.index', ['tab' => 'manage']) }}" class="btn btn-outline-secondary btn-sm">Back to Leads</a>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			@forelse($activities as $activity)
				<div class="border rounded-3 p-3 mb-3">
					<div class="d-flex justify-content-between align-items-center mb-1">
						<span class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $activity->activity_type) }}</span>
						<small class="text-secondary-light">{{ $activity->created_at?->diffForHumans() }}</small>
					</div>
					<p class="mb-1">{{ $activity->description ?? '-' }}</p>
					@if($activity->notes)
						<p class="text-secondary small mb-0">{{ $activity->notes }}</p>
					@endif
				</div>
			@empty
				<p class="text-secondary mb-0">No activities logged for this lead yet.</p>
			@endforelse

			@if($activities->hasPages())
				<div class="pt-3">
					{{ $activities->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

