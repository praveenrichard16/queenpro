@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Blog Posts</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Blog Posts</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif
	@if(session('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ session('error') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-xl-4 col-lg-6">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y">
							<iconify-icon icon="mage:search"></iconify-icon>
						</span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Post title or excerpt" name="search" value="{{ request('search') }}">
					</div>
				</div>
				<div class="col-xl-3 col-lg-6">
					<label class="form-label text-secondary-light">Category</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="category">
						<option value="">All categories</option>
						@foreach($categories as $id => $name)
							<option value="{{ $id }}" @selected(request('category') == $id)>{{ $name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xl-3 col-lg-6">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">Any status</option>
						<option value="published" @selected(request('status') === 'published')>Published</option>
						<option value="draft" @selected(request('status') === 'draft')>Draft</option>
					</select>
				</div>
				<div class="col-xl-2 col-lg-6 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Apply</button>
					<a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
			</form>

			<div class="d-flex flex-wrap gap-2 mt-24 justify-content-between">
				<div class="d-flex flex-wrap gap-3 align-items-center">
						<span class="badge bg-neutral-100 text-secondary-light px-16 py-8 radius-12 fw-medium">
							Total Posts: {{ $posts->total() }}
						</span>
				</div>
				<a href="{{ route('admin.blog.posts.create') }}" class="btn btn-warning radius-12 text-white d-inline-flex align-items-center gap-2">
					<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					Write New Post
				</a>
			</div>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th scope="col">Post</th>
							<th scope="col">Category</th>
							<th scope="col">Author</th>
							<th scope="col">Status</th>
							<th scope="col" class="text-end">Updated</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($posts as $post)
							<tr>
								<td>
									<h6 class="text-md fw-semibold mb-4">{{ $post->title }}</h6>
									<p class="text-sm text-secondary-light mb-0">{{ \Illuminate\Support\Str::limit($post->excerpt, 120) ?: '—' }}</p>
								</td>
								<td><span class="text-sm text-secondary-light fw-semibold">{{ $post->category->name ?? 'Uncategorised' }}</span></td>
								<td><span class="text-sm text-secondary-light">{{ $post->author?->name ?? 'System' }}</span></td>
								<td>
									<div class="d-flex flex-column gap-2">
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $post->is_published ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
											{{ $post->is_published ? 'Published' : 'Draft' }}
										</span>
										@if($post->is_featured)
											<span class="badge bg-warning text-white radius-12 align-self-start px-12 py-6">Featured</span>
										@endif
									</div>
								</td>
								<td class="text-end text-sm text-secondary-light">
									@if($post->published_at)
										Published {{ $post->published_at->diffForHumans() }}
									@else
										Updated {{ $post->updated_at->diffForHumans() }}
									@endif
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.blog.posts.edit', $post) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Edit">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.blog.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Move this post to trash?')">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Delete">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No data" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No posts yet</h6>
									<p class="text-secondary-light mb-0">Start publishing to engage shoppers with trend stories and buying guides.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($posts->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $posts->withQueryString()->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

