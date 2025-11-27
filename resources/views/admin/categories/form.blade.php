@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Category' : 'Add Category')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $category->exists ? 'Edit Category' : 'Add Category' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.categories.index') }}" class="hover-text-primary">Categories</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $category->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="row g-4" enctype="multipart/form-data">
				@csrf
				@if($category->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name</label>
					<input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Slug <span class="text-muted small">(auto-generated if empty)</span></label>
					<input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('slug') is-invalid @enderror" placeholder="category-slug">
					@error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Leave empty to auto-generate from name</div>
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Parent Category</label>
					<select name="parent_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('parent_id') is-invalid @enderror">
						<option value="">— None (Top Level) —</option>
						@foreach($parents as $parent)
							<option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
						@endforeach
					</select>
					@error('parent_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Category Image</label>
					<input type="file" name="image" class="form-control bg-neutral-50 radius-12 h-56-px @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
					@error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload JPG/PNG/WebP up to 2MB.</div>
					@if($category->image_path)
						<div class="mt-3">
							<img src="{{ $category->image_path }}" alt="{{ $category->image_alt_text ?? $category->name }}" class="img-fluid rounded border" style="max-height:160px; object-fit:cover;">
						</div>
					@endif
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Icon</label>
					<input type="file" name="icon" class="form-control bg-neutral-50 radius-12 h-56-px @error('icon') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp,.svg">
					@error('icon') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Optional icon file (JPG/PNG/WebP/SVG, max 1MB).</div>
					@if($category->icon_path)
						<div class="mt-3 d-inline-flex align-items-center gap-3">
							<img src="{{ $category->icon_path }}" alt="{{ $category->name }} icon" class="rounded border p-2" style="height:64px; width:64px; object-fit:contain;">
							<span class="text-secondary-light small">Current icon</span>
						</div>
					@endif
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Image Alt Text</label>
					<input type="text" name="image_alt_text" value="{{ old('image_alt_text', $category->image_alt_text) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('image_alt_text') is-invalid @enderror" placeholder="Describe the category image for accessibility">
					@error('image_alt_text') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="4" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Title</label>
					<input type="text" name="meta_title" value="{{ old('meta_title', $category->meta_title) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('meta_title') is-invalid @enderror" placeholder="SEO title for this category">
					@error('meta_title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Description</label>
					<textarea name="meta_description" rows="3" class="form-control bg-neutral-50 radius-12 @error('meta_description') is-invalid @enderror" placeholder="SEO description for search results">{{ old('meta_description', $category->meta_description) }}</textarea>
					@error('meta_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="is_active" name="is_active" @checked(old('is_active', $category->is_active ?? true))>
						<label class="form-check-label ms-8" for="is_active">
							Active
						</label>
					</div>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $category->exists ? 'Update Category' : 'Create Category' }}</button>
					<a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

