@extends('layouts.admin')

@section('title', 'Edit Lead Source')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Edit Lead Source</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.lead-sources.index', ['tab' => request('tab', 'manage')]) }}" class="hover-text-primary">Lead Sources</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Edit</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.lead-sources.update', $leadSource) }}" class="row g-4">
				@csrf
				@method('PUT')
				<input type="hidden" name="tab" value="{{ request('tab', 'manage') }}">

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Lead Source Information</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $leadSource->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Slug</label>
					<input type="text" name="slug" value="{{ old('slug', $leadSource->slug) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('slug') is-invalid @enderror" placeholder="Auto-generated if left empty">
					@error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Leave empty to auto-generate from name</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror">{{ old('description', $leadSource->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $leadSource->is_active))>
						<label class="form-check-label" for="is_active">Active</label>
					</div>
				</div>

				<div class="col-12">
					<div class="d-flex gap-2">
						<button type="submit" class="btn btn-primary radius-12 h-56-px">Update Lead Source</button>
						<a href="{{ route('admin.lead-sources.index', ['tab' => request('tab', 'manage')]) }}" class="btn btn-outline-secondary radius-12 h-56-px">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

