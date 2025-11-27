@extends('layouts.admin')

@section('title', 'Edit About Us')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Edit About Us</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.cms.about-us.edit') }}" class="hover-text-primary">About Us</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Edit</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	@if($errors->any())
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please review the highlighted fields and try again.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.cms.about-us.update') }}" class="row g-4" id="about-us-form" enctype="multipart/form-data">
				@csrf
				@method('PUT')

				<div class="col-12">
					<label class="form-label text-secondary-light">Title</label>
					<input type="text" name="title" class="form-control bg-neutral-50 radius-12 h-56-px @error('title') is-invalid @enderror" value="{{ old('title', $page->title) }}" required>
					@error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 mb-4">
					<label class="form-label text-secondary-light">Content</label>
					<div class="quill-editor bg-neutral-50 radius-12 @error('content') border border-danger @enderror" id="about-us-editor" style="min-height:400px;">
						{!! old('content', $page->content) !!}
					</div>
					<input type="hidden" name="content" id="about-us-content" value="{{ old('content', $page->content) }}">
					@error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-3">Images</h6>
				</div>

				<div class="col-md-4">
					<label class="form-label text-secondary-light">Image 1</label>
					<input type="file" name="image_1" class="form-control bg-neutral-50 radius-12 h-56-px @error('image_1') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
					@error('image_1') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload JPG/PNG/WebP, max 2MB.</div>
					@if($page->image_1)
						<div class="mt-3">
							<label class="form-label text-secondary-light d-block">Current Image 1</label>
							<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex">
								<img src="{{ asset(ltrim($page->image_1, '/')) }}" class="rounded" style="max-height:140px" alt="About Us Image 1">
							</div>
						</div>
					@endif
				</div>

				<div class="col-md-4">
					<label class="form-label text-secondary-light">Image 2</label>
					<input type="file" name="image_2" class="form-control bg-neutral-50 radius-12 h-56-px @error('image_2') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
					@error('image_2') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload JPG/PNG/WebP, max 2MB.</div>
					@if($page->image_2)
						<div class="mt-3">
							<label class="form-label text-secondary-light d-block">Current Image 2</label>
							<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex">
								<img src="{{ asset(ltrim($page->image_2, '/')) }}" class="rounded" style="max-height:140px" alt="About Us Image 2">
							</div>
						</div>
					@endif
				</div>

				<div class="col-md-4">
					<label class="form-label text-secondary-light">Image 3</label>
					<input type="file" name="image_3" class="form-control bg-neutral-50 radius-12 h-56-px @error('image_3') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
					@error('image_3') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload JPG/PNG/WebP, max 2MB.</div>
					@if($page->image_3)
						<div class="mt-3">
							<label class="form-label text-secondary-light d-block">Current Image 3</label>
							<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex">
								<img src="{{ asset(ltrim($page->image_3, '/')) }}" class="rounded" style="max-height:140px" alt="About Us Image 3">
							</div>
						</div>
					@endif
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-3">SEO Settings</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Title</label>
					<input type="text" name="meta_title" class="form-control bg-neutral-50 radius-12 @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $page->meta_title) }}" placeholder="SEO meta title">
					@error('meta_title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light d-block">Status</label>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $page->is_active))>
						<label class="form-check-label" for="is_active">Active</label>
					</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Meta Description</label>
					<textarea name="meta_description" rows="3" class="form-control bg-neutral-50 radius-12 @error('meta_description') is-invalid @enderror" placeholder="SEO meta description">{{ old('meta_description', $page->meta_description) }}</textarea>
					@error('meta_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-2 mt-8">
					<button type="submit" class="btn btn-primary radius-12 px-24">Update Page</button>
					<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
	<script src="{{ asset('wowdash/assets/js/editor.quill.js') }}"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const editorElement = document.getElementById('about-us-editor');
			const hiddenInput = document.getElementById('about-us-content');

			if (!editorElement || !hiddenInput || typeof Quill === 'undefined') {
				return;
			}

			const quill = new Quill(editorElement, {
				theme: 'snow',
				placeholder: 'Write your page content here...',
				modules: {
					toolbar: [
						[{ header: [1, 2, 3, false] }],
						['bold', 'italic', 'underline', 'strike'],
						[{ color: [] }, { background: [] }],
						[{ list: 'ordered' }, { list: 'bullet' }],
						['blockquote', 'link', 'image', 'video'],
						['clean']
					]
				}
			});

			quill.on('text-change', function () {
				hiddenInput.value = quill.root.innerHTML;
			});

			const initialContent = hiddenInput.value;
			if (initialContent) {
				quill.root.innerHTML = initialContent;
			}

			document.getElementById('about-us-form').addEventListener('submit', function () {
				hiddenInput.value = quill.root.innerHTML;
			});
		});
	</script>
@endpush

