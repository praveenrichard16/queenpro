@extends('layouts.admin')

@section('title', $tag->exists ? 'Edit Tag' : 'Add Tag')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $tag->exists ? 'Edit Tag' : 'Add Tag' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.tags.index') }}" class="hover-text-primary">Tags</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $tag->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $tag->exists ? route('admin.tags.update', $tag) : route('admin.tags.store') }}" class="row g-4">
				@csrf
				@if($tag->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name</label>
					<input type="text" name="name" value="{{ old('name', $tag->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Slug <span class="text-muted small">(auto-generated if empty)</span></label>
					<input type="text" name="slug" value="{{ old('slug', $tag->slug) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('slug') is-invalid @enderror" placeholder="tag-slug">
					@error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Leave empty to auto-generate from name</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror" placeholder="Optional short description for this tag">{{ old('description', $tag->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Title</label>
					<input type="text" name="meta_title" value="{{ old('meta_title', $tag->meta_title) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('meta_title') is-invalid @enderror" placeholder="SEO title for search engines">
					@error('meta_title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Description</label>
					<textarea name="meta_description" rows="3" class="form-control bg-neutral-50 radius-12 @error('meta_description') is-invalid @enderror" placeholder="SEO description for search results">{{ old('meta_description', $tag->meta_description) }}</textarea>
					@error('meta_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $tag->exists ? 'Update Tag' : 'Create Tag' }}</button>
					<a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

