@extends('layouts.admin')

@section('title', 'Add Lead')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Add Lead</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.leads.index') }}" class="hover-text-primary">Leads</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Add</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.leads.store') }}" class="row g-4">
				@csrf

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Basic Information</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Email</label>
					<input type="email" name="email" value="{{ old('email') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('email') is-invalid @enderror">
					@error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Phone</label>
					<input type="text" name="phone" value="{{ old('phone') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('phone') is-invalid @enderror">
					@error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Customer</label>
					<select name="user_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('user_id') is-invalid @enderror">
						<option value="">Select Customer</option>
						@foreach($users as $user)
							<option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
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
							<option value="{{ $source->id }}" @selected(old('lead_source_id') == $source->id)>{{ $source->name }}</option>
						@endforeach
					</select>
					@error('lead_source_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Lead Stage</label>
					<select name="lead_stage_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('lead_stage_id') is-invalid @enderror">
						<option value="">Select Stage</option>
						@foreach($leadStages as $stage)
							<option value="{{ $stage->id }}" @selected(old('lead_stage_id') == $stage->id)>{{ $stage->name }}</option>
						@endforeach
					</select>
					@error('lead_stage_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Expected Value</label>
					<input type="number" name="expected_value" value="{{ old('expected_value') }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('expected_value') is-invalid @enderror">
					@error('expected_value') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Assign To Staff</label>
					<select name="assigned_to" class="form-select bg-neutral-50 radius-12 h-56-px @error('assigned_to') is-invalid @enderror">
						<option value="">Unassigned</option>
						@foreach($users as $user)
							<option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>{{ $user->name }}</option>
						@endforeach
					</select>
					<div class="form-text mt-1">Leads can only be assigned to staff members.</div>
					@error('assigned_to') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Notes</label>
					<textarea name="notes" rows="4" class="form-control bg-neutral-50 radius-12 @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
					@error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="d-flex gap-2">
						<button type="submit" class="btn btn-primary radius-12 h-56-px">Create Lead</button>
						<a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary radius-12 h-56-px">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

