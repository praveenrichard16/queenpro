@extends('layouts.admin')

@section('title', 'Edit ' . $page->type_label)

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Edit {{ $page->type_label }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.cms.legal-pages.index') }}" class="hover-text-primary">Legal Pages</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Edit</li>
		</ul>
	</div>

	@if($errors->any())
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please review the highlighted fields and try again.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.cms.legal-pages.update', $page->type) }}" class="row g-4" id="legal-page-form">
				@csrf
				@method('PUT')

				<div class="col-12">
					<label class="form-label text-secondary-light">Title</label>
					<input type="text" name="title" class="form-control bg-neutral-50 radius-12 h-56-px @error('title') is-invalid @enderror" value="{{ old('title', $page->title) }}" required>
					@error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 mb-4">
					<label class="form-label text-secondary-light">Content</label>
					<div class="quill-editor bg-neutral-50 radius-12 @error('content') border border-danger @enderror" id="legal-page-editor" style="min-height:400px;">
						{!! old('content', $page->content) !!}
					</div>
					<input type="hidden" name="content" id="legal-page-content" value="{{ old('content', $page->content) }}">
					@error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
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
					<a href="{{ route('admin.cms.legal-pages.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
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
			const editorElement = document.getElementById('legal-page-editor');
			const hiddenInput = document.getElementById('legal-page-content');

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

			document.getElementById('legal-page-form').addEventListener('submit', function () {
				hiddenInput.value = quill.root.innerHTML;
			});
		});
	</script>
@endpush

