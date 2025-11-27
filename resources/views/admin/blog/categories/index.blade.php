@extends('layouts.admin')

@section('title', 'Blog Categories')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Blog Categories</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Blog Categories</li>
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
		<div class="card-body p-24 d-flex flex-wrap gap-2 justify-content-between align-items-center">
			<div>
				<p class="mb-0 text-secondary-light">Organise your blog content by creating category buckets.</p>
			</div>
			<a href="{{ route('admin.blog.categories.create') }}" class="btn btn-warning radius-12 text-white d-inline-flex align-items-center gap-2">
				<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
				Add Category
			</a>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th scope="col">Category</th>
							<th scope="col">Visibility</th>
							<th scope="col" class="text-center">Posts</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($categories as $category)
							<tr>
								<td>
									<div class="d-flex align-items-center gap-3">
										@if($category->icon_path)
											<span class="w-48-px h-48-px rounded-circle bg-neutral-100 d-inline-flex align-items-center justify-content-center">
												<img src="{{ asset('storage/'.$category->icon_path) }}" alt="{{ $category->icon_alt_text ?? $category->name }}" class="img-fluid rounded-circle" style="width:36px;height:36px;object-fit:cover;">
											</span>
										@endif
										<div>
											<h6 class="text-md fw-semibold mb-4">{{ $category->name }}</h6>
											<p class="text-sm text-secondary-light mb-0">{{ \Illuminate\Support\Str::limit($category->description, 90) ?: '—' }}</p>
										</div>
									</div>
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $category->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
										{{ $category->is_active ? 'Active' : 'Hidden' }}
									</span>
								</td>
								<td class="text-center fw-semibold">{{ $category->posts_count }}</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.blog.categories.edit', $category) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="4" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No data" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No categories yet</h6>
									<p class="text-secondary-light mb-0">Create your first blog category to start grouping posts.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator && $categories->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $categories->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

