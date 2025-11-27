@php
	$activities = $activities ?? collect();
@endphp

<div class="activity-log">
	@forelse($activities as $activity)
		<div class="border rounded-3 p-3 mb-2">
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
		<p class="text-secondary mb-0">No activity recorded yet.</p>
	@endforelse
</div>

