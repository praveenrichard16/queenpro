@extends('layouts.admin')

@section('title', 'New Support Category')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Create Support Category</h6>
			<p class="text-secondary-light mb-0">Group tickets and define default handling priorities.</p>
		</div>
		<a href="{{ route('admin.support.categories.index') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
			Back to categories
		</a>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			<form action="{{ route('admin.support.categories.store') }}" method="POST" class="row g-4">
				@csrf
				<div class="col-md-6">
					<label class="form-label text-secondary-light">Name</label>
					<input type="text" name="name" class="form-control bg-neutral-50 radius-12 @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
					@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-6">
					<label class="form-label text-secondary-light">Default priority</label>
					<select name="default_priority" class="form-select bg-neutral-50 radius-12 @error('default_priority') is-invalid @enderror">
						<option value="">Not set</option>
						@foreach($priorities as $priority)
							<option value="{{ $priority->value }}" @selected(old('default_priority') === $priority->value)>{{ $priority->label() }}</option>
						@endforeach
					</select>
					@error('default_priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror" placeholder="Explain when to use this category">{{ old('description') }}</textarea>
					@error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-12">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" id="category-active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
						<label class="form-check-label text-secondary-light" for="category-active">Category is active and selectable</label>
					</div>
				</div>
				<div class="col-12">
					<button class="btn btn-primary radius-12 px-24" type="submit">Create category</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

