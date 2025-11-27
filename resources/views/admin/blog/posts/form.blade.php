@extends('layouts.admin')

@section('title', $post->exists ? 'Edit Blog Post' : 'Create Blog Post')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $post->exists ? 'Edit Blog Post' : 'Create Blog Post' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.blog.posts.index') }}" class="hover-text-primary">Blog Posts</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $post->exists ? 'Edit' : 'Create' }}</li>
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
			<form method="POST" enctype="multipart/form-data" action="{{ $post->exists ? route('admin.blog.posts.update', $post) : route('admin.blog.posts.store') }}" class="row g-4" id="blog-post-form">
				@csrf
				@if($post->exists)
					@method('PUT')
				@endif

				<div class="col-lg-8">
					<label class="form-label text-secondary-light">Title</label>
					<input type="text" name="title" class="form-control bg-neutral-50 radius-12 h-56-px @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
					@error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Category</label>
					<select name="blog_category_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('blog_category_id') is-invalid @enderror" required>
						<option value="">Select category</option>
						@foreach($categories as $id => $name)
							<option value="{{ $id }}" @selected(old('blog_category_id', $post->blog_category_id) == $id)>{{ $name }}</option>
						@endforeach
					</select>
					@error('blog_category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Excerpt</label>
					<textarea name="excerpt" rows="3" class="form-control bg-neutral-50 radius-12 @error('excerpt') is-invalid @enderror" placeholder="Short summary shown on listings">{{ old('excerpt', $post->excerpt) }}</textarea>
					@error('excerpt') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 mb-4">
					<label class="form-label text-secondary-light">Content</label>
					<div class="quill-editor bg-neutral-50 radius-12 @error('content') border border-danger @enderror" id="blog-post-editor" style="min-height:260px;">
						{!! old('content', $post->content) !!}
					</div>
					<input type="hidden" name="content" id="blog-post-content" value="{{ old('content', $post->content) }}">
					@error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Featured Image</label>
					<input type="file" name="featured_image" class="form-control bg-neutral-50 radius-12 h-56-px @error('featured_image') is-invalid @enderror">
					@error('featured_image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Recommended: 1200×675px JPG/PNG (max 4MB).</div>
				</div>
				@if($post->featured_image_path)
					<div class="col-lg-6">
						<label class="form-label text-secondary-light d-block">Current Featured Image</label>
						<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex">
							<img src="{{ asset('storage/'.$post->featured_image_path) }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="img-fluid rounded" style="max-height:140px;">
						</div>
					</div>
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Featured Image Alt Text</label>
					<input type="text" name="featured_image_alt" class="form-control bg-neutral-50 radius-12 h-56-px @error('featured_image_alt') is-invalid @enderror" value="{{ old('featured_image_alt', $post->featured_image_alt) }}" placeholder="Describe the image for accessibility">
					@error('featured_image_alt') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Title</label>
					<input type="text" name="meta_title" class="form-control bg-neutral-50 radius-12 @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $post->meta_title) }}" placeholder="SEO meta title">
					@error('meta_title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-12">
					<label class="form-label text-secondary-light">Meta Description</label>
					<textarea name="meta_description" rows="3" class="form-control bg-neutral-50 radius-12 @error('meta_description') is-invalid @enderror" placeholder="SEO meta description">{{ old('meta_description', $post->meta_description) }}</textarea>
					@error('meta_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-12">
					<label class="form-label text-secondary-light">Meta Keywords</label>
					<input type="text" name="meta_keywords" class="form-control bg-neutral-50 radius-12 @error('meta_keywords') is-invalid @enderror" value="{{ old('meta_keywords', $post->meta_keywords) }}" placeholder="Comma-separated keywords">
					@error('meta_keywords') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Tags</label>
					<select name="tag_ids[]" class="form-select bg-neutral-50 radius-12 @error('tag_ids') is-invalid @enderror" multiple size="6">
						@foreach($tags as $id => $name)
							<option value="{{ $id }}" @selected(collect(old('tag_ids', $selectedTags))->contains($id))>{{ $name }}</option>
						@endforeach
					</select>
					@error('tag_ids') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					@error('tag_ids.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Hold Ctrl (Cmd on Mac) to select multiple tags.</div>
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Publish At</label>
					<input type="datetime-local" name="published_at" class="form-control bg-neutral-50 radius-12 h-56-px @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
					@error('published_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Leave blank to publish immediately when toggled live.</div>
				</div>
				<div class="col-lg-4">
					<label class="form-label text-secondary-light d-block">Post Status</label>
					<div class="form-check form-switch mb-12">
						<input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $post->is_published))>
						<label class="form-check-label" for="is_published">Published</label>
					</div>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))>
						<label class="form-check-label" for="is_featured">Feature on storefront</label>
					</div>
				</div>

				<div class="col-12 d-flex gap-2 mt-8">
					<button class="btn btn-primary radius-12 px-24">{{ $post->exists ? 'Update Post' : 'Publish Post' }}</button>
					<a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
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
			const editorElement = document.getElementById('blog-post-editor');
			const hiddenInput = document.getElementById('blog-post-content');

			if (!editorElement || !hiddenInput || typeof Quill === 'undefined') {
				return;
			}

			const quill = new Quill(editorElement, {
				theme: 'snow',
				placeholder: 'Write your post content here...',
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

			document.getElementById('blog-post-form').addEventListener('submit', function () {
				hiddenInput.value = quill.root.innerHTML;
			});
		});
	</script>
@endpush

