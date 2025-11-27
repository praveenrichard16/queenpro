@extends('layouts.admin')

@section('title', 'Blog Tags')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Blog Tags</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Blog Tags</li>
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
				<p class="mb-0 text-secondary-light">Create tags to help shoppers discover posts by topic.</p>
			</div>
			<a href="{{ route('admin.blog.tags.create') }}" class="btn btn-warning radius-12 text-white d-inline-flex align-items-center gap-2">
				<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
				Add Tag
			</a>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th scope="col">Tag</th>
							<th scope="col">Description</th>
							<th scope="col" class="text-center">Posts</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($tags as $tag)
							<tr>
								<td class="fw-semibold text-md">{{ $tag->name }}</td>
								<td class="text-secondary-light text-sm">{{ \Illuminate\Support\Str::limit($tag->description, 110) ?: '—' }}</td>
								<td class="text-center fw-semibold">{{ $tag->posts_count }}</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.blog.tags.edit', $tag) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.blog.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
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
									<h6 class="fw-semibold mb-8">No tags yet</h6>
									<p class="text-secondary-light mb-0">Add descriptive tags to improve blog searchability.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($tags instanceof \Illuminate\Pagination\LengthAwarePaginator && $tags->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $tags->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

