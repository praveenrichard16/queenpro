@extends('layouts.admin')

@section('title', 'Add Lead Stage')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Add Lead Stage</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.lead-stages.index') }}" class="hover-text-primary">Lead Stages</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Add</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.lead-stages.store') }}" class="row g-4">
				@csrf

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Lead Stage Information</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Slug</label>
					<input type="text" name="slug" value="{{ old('slug') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('slug') is-invalid @enderror" placeholder="Auto-generated if left empty">
					@error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Leave empty to auto-generate from name</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Sort Order</label>
					<input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('sort_order') is-invalid @enderror">
					@error('sort_order') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Lower numbers appear first</div>
				</div>

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Stage Flags</h6>
				</div>

				<div class="col-12">
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" @checked(old('is_default'))>
						<label class="form-check-label" for="is_default">Set as Default Stage</label>
						<div class="form-text">New leads will be assigned to this stage by default</div>
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" name="is_won" id="is_won" value="1" @checked(old('is_won'))>
						<label class="form-check-label" for="is_won">Mark as Won Stage</label>
						<div class="form-text">Leads in this stage are considered successful conversions</div>
					</div>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" name="is_lost" id="is_lost" value="1" @checked(old('is_lost'))>
						<label class="form-check-label" for="is_lost">Mark as Lost Stage</label>
						<div class="form-text">Leads in this stage are considered lost opportunities</div>
					</div>
				</div>

				<div class="col-12">
					<div class="d-flex gap-2">
						<button type="submit" class="btn btn-primary radius-12 h-56-px">Create Lead Stage</button>
						<a href="{{ route('admin.lead-stages.index') }}" class="btn btn-outline-secondary radius-12 h-56-px">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

