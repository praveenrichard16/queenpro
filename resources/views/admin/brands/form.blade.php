@extends('layouts.admin')

@section('title', $brand->exists ? 'Edit Brand' : 'Add Brand')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $brand->exists ? 'Edit Brand' : 'Add Brand' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.brands.index') }}" class="hover-text-primary">Brands</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $brand->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" class="row g-4" enctype="multipart/form-data">
				@csrf
				@if($brand->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Brand Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $brand->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Brand Logo</label>
					<input type="file" name="logo" class="form-control bg-neutral-50 radius-12 h-56-px @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,.svg">
					@error('logo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload brand logo (JPG/PNG/WebP/SVG, max 2MB).</div>
					@if($brand->logo_path)
						<div class="mt-3">
							<img src="{{ $brand->logo_path }}" alt="{{ $brand->name }} logo" class="img-fluid rounded border p-2" style="max-height:120px; max-width:200px; object-fit:contain;">
							<p class="text-secondary-light small mt-2 mb-0">Current logo</p>
						</div>
					@endif
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Brand Icon</label>
					<input type="file" name="icon" class="form-control bg-neutral-50 radius-12 h-56-px @error('icon') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,.svg">
					@error('icon') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload brand icon (JPG/PNG/WebP/SVG, max 1MB).</div>
					@if($brand->icon_path)
						<div class="mt-3 d-inline-flex align-items-center gap-3">
							<img src="{{ $brand->icon_path }}" alt="{{ $brand->name }} icon" class="rounded border p-2" style="height:64px; width:64px; object-fit:contain;">
							<span class="text-secondary-light small">Current icon</span>
						</div>
					@endif
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Brand Image</label>
					<input type="file" name="image" class="form-control bg-neutral-50 radius-12 h-56-px @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
					@error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload brand image (JPG/PNG/WebP, max 2MB).</div>
					@if($brand->image_path)
						<div class="mt-3">
							<img src="{{ $brand->image_path }}" alt="{{ $brand->image_alt_text ?? $brand->name }}" class="img-fluid rounded border" style="max-height:160px; object-fit:cover;">
							<p class="text-secondary-light small mt-2 mb-0">Current image</p>
						</div>
					@endif
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Image Alt Text</label>
					<input type="text" name="image_alt_text" value="{{ old('image_alt_text', $brand->image_alt_text) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('image_alt_text') is-invalid @enderror" placeholder="Describe the brand image for accessibility">
					@error('image_alt_text') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="4" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror" placeholder="Brand description">{{ old('description', $brand->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<h6 class="fw-semibold mb-16">SEO Settings</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Title</label>
					<input type="text" name="meta_title" value="{{ old('meta_title', $brand->meta_title) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('meta_title') is-invalid @enderror" placeholder="SEO title for this brand">
					@error('meta_title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Description</label>
					<textarea name="meta_description" rows="3" class="form-control bg-neutral-50 radius-12 @error('meta_description') is-invalid @enderror" placeholder="SEO description for search results">{{ old('meta_description', $brand->meta_description) }}</textarea>
					@error('meta_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="is_active" name="is_active" @checked(old('is_active', $brand->is_active ?? true))>
						<label class="form-check-label ms-8" for="is_active">
							Active
						</label>
					</div>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $brand->exists ? 'Update Brand' : 'Create Brand' }}</button>
					<a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

