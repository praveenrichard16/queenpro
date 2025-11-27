@extends('layouts.admin')

@section('title', 'Edit Lead')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Edit Lead</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.leads.index', ['tab' => request('tab', 'manage')]) }}" class="hover-text-primary">Leads</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Edit</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="row g-4">
				@csrf
				@method('PUT')
				<input type="hidden" name="tab" value="{{ request('tab', 'manage') }}">

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Basic Information</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $lead->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Email</label>
					<input type="email" name="email" value="{{ old('email', $lead->email) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('email') is-invalid @enderror">
					@error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Phone</label>
					<input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('phone') is-invalid @enderror">
					@error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Customer</label>
					<select name="user_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('user_id') is-invalid @enderror">
						<option value="">Select Customer</option>
						@foreach($users as $user)
							<option value="{{ $user->id }}" @selected(old('user_id', $lead->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
						@endforeach
					</select>
					@error('user_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Lead Details</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Lead Source</label>
					<select name="lead_source_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('lead_source_id') is-invalid @enderror">
						<option value="">Select Source</option>
						@foreach($leadSources as $source)
							<option value="{{ $source->id }}" @selected(old('lead_source_id', $lead->lead_source_id) == $source->id)>{{ $source->name }}</option>
						@endforeach
					</select>
					@error('lead_source_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Lead Stage</label>
					<select name="lead_stage_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('lead_stage_id') is-invalid @enderror">
						<option value="">Select Stage</option>
						@foreach($leadStages as $stage)
							<option value="{{ $stage->id }}" @selected(old('lead_stage_id', $lead->lead_stage_id) == $stage->id)>{{ $stage->name }}</option>
						@endforeach
					</select>
					@error('lead_stage_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Expected Value</label>
					<input type="number" name="expected_value" value="{{ old('expected_value', $lead->expected_value) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('expected_value') is-invalid @enderror">
					@error('expected_value') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Assign To Staff</label>
					@php
						$currentAssignee = $lead->assignee;
						$currentAssigneeIsStaff = $currentAssignee && $currentAssignee->is_staff && !$currentAssignee->is_admin;
					@endphp
					@if($lead->assigned_to && !$currentAssigneeIsStaff)
						<div class="alert alert-warning mb-2">
							<strong>Warning:</strong> This lead is currently assigned to a non-staff user ({{ $currentAssignee->name ?? 'Unknown' }}). Please reassign to a staff member or leave unassigned.
						</div>
					@endif
					<select name="assigned_to" class="form-select bg-neutral-50 radius-12 h-56-px @error('assigned_to') is-invalid @enderror">
						<option value="" @selected(!$currentAssigneeIsStaff || old('assigned_to', $lead->assigned_to) == '')>Unassigned</option>
						@foreach($users as $user)
							@php
								$isStaff = $user->is_staff && !$user->is_admin;
								$isCurrentAssignee = old('assigned_to', $lead->assigned_to) == $user->id && $isStaff;
							@endphp
							@if($isStaff)
								<option value="{{ $user->id }}" @selected($isCurrentAssignee)>
									{{ $user->name }}
								</option>
							@endif
						@endforeach
					</select>
					<div class="form-text mt-1">Leads can only be assigned to staff members.</div>
					@error('assigned_to') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Followup Planning</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Next Followup Date</label>
					<input type="date" name="next_followup_date" value="{{ old('next_followup_date', $lead->next_followup_date?->format('Y-m-d')) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('next_followup_date') is-invalid @enderror">
					@error('next_followup_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Next Followup Time</label>
					<input type="time" name="next_followup_time" value="{{ old('next_followup_time', $lead->next_followup_time) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('next_followup_time') is-invalid @enderror">
					@error('next_followup_time') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Lead Score</label>
					<input type="number" name="lead_score" value="{{ old('lead_score', $lead->lead_score) }}" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('lead_score') is-invalid @enderror">
					@error('lead_score') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Notes</label>
					<textarea name="notes" rows="4" class="form-control bg-neutral-50 radius-12 @error('notes') is-invalid @enderror">{{ old('notes', $lead->notes) }}</textarea>
					@error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="d-flex gap-2">
						<button type="submit" class="btn btn-primary radius-12 h-56-px">Update Lead</button>
						<a href="{{ route('admin.leads.index', ['tab' => request('tab', 'manage')]) }}" class="btn btn-outline-secondary radius-12 h-56-px">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

