@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Blog Category' : 'Add Blog Category')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $category->exists ? 'Edit Blog Category' : 'Add Blog Category' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.blog.categories.index') }}" class="hover-text-primary">Blog Categories</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $category->exists ? 'Edit' : 'Add' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" enctype="multipart/form-data" action="{{ $category->exists ? route('admin.blog.categories.update', $category) : route('admin.blog.categories.store') }}" class="row g-4">
				@csrf
				@if($category->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name</label>
					<input type="text" name="name" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Visibility</label>
					<select name="is_active" class="form-select bg-neutral-50 radius-12 h-56-px">
						<option value="1" @selected(old('is_active', $category->is_active))>Active</option>
						<option value="0" @selected(!old('is_active', $category->is_active))>Hidden</option>
					</select>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="4" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror" placeholder="Short summary for this category">{{ old('description', $category->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Icon</label>
					<input type="file" name="icon" class="form-control bg-neutral-50 radius-12 h-56-px @error('icon') is-invalid @enderror">
					@error('icon') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Recommended: 120×120px transparent PNG/SVG (max 2MB).</div>
				</div>
				@if($category->icon_path)
					<div class="col-lg-6">
						<label class="form-label text-secondary-light d-block">Current Icon</label>
						<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex align-items-center justify-content-center" style="width:120px;height:120px;">
							<img src="{{ asset('storage/'.$category->icon_path) }}" alt="{{ $category->icon_alt_text ?? $category->name }}" class="img-fluid" style="max-height:88px;">
						</div>
					</div>
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Icon Alt Text</label>
					<input type="text" name="icon_alt_text" class="form-control bg-neutral-50 radius-12 h-56-px @error('icon_alt_text') is-invalid @enderror" value="{{ old('icon_alt_text', $category->icon_alt_text) }}" placeholder="Accessible description for the icon">
					@error('icon_alt_text') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Title</label>
					<input type="text" name="meta_title" class="form-control bg-neutral-50 radius-12 @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $category->meta_title) }}" placeholder="SEO meta title">
					@error('meta_title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-12">
					<label class="form-label text-secondary-light">Meta Description</label>
					<textarea name="meta_description" rows="3" class="form-control bg-neutral-50 radius-12 @error('meta_description') is-invalid @enderror" placeholder="SEO meta description">{{ old('meta_description', $category->meta_description) }}</textarea>
					@error('meta_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-2 mt-8">
					<button class="btn btn-primary radius-12 px-24">{{ $category->exists ? 'Update Category' : 'Create Category' }}</button>
					<a href="{{ route('admin.blog.categories.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

