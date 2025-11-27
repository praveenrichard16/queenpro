@extends('layouts.admin')

@section('title', $taxClass->exists ? 'Edit Tax Class' : 'Add Tax Class')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $taxClass->exists ? 'Edit Tax Class' : 'Add Tax Class' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.tax-classes.index') }}" class="hover-text-primary">Tax Classes</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $taxClass->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $taxClass->exists ? route('admin.tax-classes.update', $taxClass) : route('admin.tax-classes.store') }}" class="row g-4">
				@csrf
				@if($taxClass->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $taxClass->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Tax Rate (%) <span class="text-danger">*</span></label>
					<div class="input-group">
						<input type="number" name="rate" value="{{ old('rate', $taxClass->rate) }}" step="0.01" min="0" max="100" class="form-control bg-neutral-50 radius-12 h-56-px @error('rate') is-invalid @enderror" required>
						<span class="input-group-text bg-neutral-50">%</span>
					</div>
					@error('rate') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Enter the tax rate as a percentage (e.g., 15 for 15%).</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror" placeholder="Describe this tax class">{{ old('description', $taxClass->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="is_active" name="is_active" @checked(old('is_active', $taxClass->is_active ?? true))>
						<label class="form-check-label ms-8" for="is_active">
							Active
						</label>
					</div>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $taxClass->exists ? 'Update Tax Class' : 'Create Tax Class' }}</button>
					<a href="{{ route('admin.tax-classes.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

