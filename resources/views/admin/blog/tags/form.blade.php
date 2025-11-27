@extends('layouts.admin')

@section('title', $tag->exists ? 'Edit Blog Tag' : 'Add Blog Tag')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $tag->exists ? 'Edit Blog Tag' : 'Add Blog Tag' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.blog.tags.index') }}" class="hover-text-primary">Blog Tags</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $tag->exists ? 'Edit' : 'Add' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $tag->exists ? route('admin.blog.tags.update', $tag) : route('admin.blog.tags.store') }}" class="row g-4">
				@csrf
				@if($tag->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name</label>
					<input type="text" name="name" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" value="{{ old('name', $tag->name) }}" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="4" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror" placeholder="Optional context for this tag">{{ old('description', $tag->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-2 mt-8">
					<button class="btn btn-primary radius-12 px-24">{{ $tag->exists ? 'Update Tag' : 'Create Tag' }}</button>
					<a href="{{ route('admin.blog.tags.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

