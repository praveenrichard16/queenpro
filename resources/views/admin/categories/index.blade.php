@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Categories</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Categories</li>
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

	<div class="card border-0 mb-24">
		<div class="card-body p-24 d-flex justify-content-between align-items-center">
			<div>
				<h6 class="mb-2">Manage categories and subcategories</h6>
				<p class="mb-0 text-secondary-light">Organize your catalog hierarchy. Parent categories appear in the storefront navigation.</p>
			</div>
			<a href="{{ route('admin.categories.create') }}" class="btn btn-primary radius-12 d-inline-flex align-items-center gap-2">
				<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
				Add Category
			</a>
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Name</th>
							<th>Parent</th>
							<th>Status</th>
							<th class="text-end">Products</th>
							<th class="text-end">Subcategories</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($categories as $category)
							<tr>
								<td>
									<h6 class="text-md mb-2 fw-medium">{{ $category->name }}</h6>
									<p class="text-sm text-secondary-light mb-0">{{ $category->slug }}</p>
								</td>
								<td>{{ $category->parent?->name ?? '—' }}</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $category->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
										{{ $category->is_active ? 'Active' : 'Inactive' }}
									</span>
								</td>
								<td class="text-end">{{ $category->products_count }}</td>
								<td class="text-end">{{ $category->children_count }}</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.categories.edit', $category) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
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
								<td colspan="6" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No categories" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No categories yet</h6>
									<p class="text-secondary-light mb-0">Create your first category to organize products.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

