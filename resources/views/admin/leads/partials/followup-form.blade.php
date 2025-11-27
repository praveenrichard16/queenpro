@php
	/**
	 * @var \App\Models\Lead $lead
	 * @var \App\Models\LeadFollowup|null $followup
	 */
	$followup = $followup ?? null;
	$action = isset($followup)
		? route('admin.leads.followups.update', [$lead->id, $followup->id])
		: route('admin.leads.followups.store', $lead->id);
	$method = isset($followup) ? 'PUT' : 'POST';
@endphp

<form method="POST" action="{{ $action }}" class="row g-3">
	@csrf
	@if(isset($followup))
		@method('PUT')
	@endif

	<div class="col-md-6">
		<label class="form-label text-secondary-light">Followup Date <span class="text-danger">*</span></label>
		<input type="date" name="followup_date" class="form-control bg-neutral-50" value="{{ old('followup_date', optional($followup)->followup_date?->format('Y-m-d')) }}" required>
	</div>
	<div class="col-md-6">
		<label class="form-label text-secondary-light">Followup Time</label>
		<input type="time" name="followup_time" class="form-control bg-neutral-50" value="{{ old('followup_time', $followup->followup_time ?? '') }}">
	</div>
	<div class="col-12">
		<label class="form-label text-secondary-light">Notes</label>
		<textarea name="notes" class="form-control bg-neutral-50" rows="2">{{ old('notes', $followup->notes ?? '') }}</textarea>
	</div>
	<div class="col-12 d-flex justify-content-end">
		<button type="submit" class="btn btn-primary">{{ isset($followup) ? 'Update Followup' : 'Schedule Followup' }}</button>
	</div>
</form>

