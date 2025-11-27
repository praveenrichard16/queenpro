@extends('layouts.admin')

@section('title', 'Support Categories')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Ticket Categories</h6>
			<p class="text-secondary-light mb-0">Organise support requests by type and define default priorities.</p>
		</div>
		<a href="{{ route('admin.support.categories.create') }}" class="btn btn-primary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:add-circle-linear" class="text-lg"></iconify-icon>
			New Category
		</a>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			@if($categories->isEmpty())
				<div class="py-40 text-center text-secondary-light">
					No categories yet. <a href="{{ route('admin.support.categories.create') }}" class="text-primary fw-semibold">Create your first category</a>.
				</div>
			@else
				<div class="table-responsive">
					<table class="table align-middle mb-0">
						<thead>
							<tr>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Name</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Description</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Default priority</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Status</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Tickets</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($categories as $category)
							<tr>
								<td class="fw-semibold text-sm">{{ $category->name }}</td>
								<td class="text-sm text-secondary-light">{{ $category->description ?: '—' }}</td>
								<td class="text-sm text-secondary-light">{{ $category->default_priority ? ucfirst($category->default_priority) : 'Not set' }}</td>
								<td>
									<span class="badge radius-8 text-xs fw-semibold {{ $category->is_active ? 'bg-success-50 text-success' : 'bg-neutral-100 text-secondary-light' }}">
										{{ $category->is_active ? 'Active' : 'Archived' }}
									</span>
								</td>
								<td class="text-sm text-secondary-light">{{ $category->tickets_count }}</td>
								<td class="text-end">
									<div class="d-inline-flex align-items-center gap-2">
										<a href="{{ route('admin.support.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary radius-12 px-16">Edit</a>
										@if($category->is_active)
											<form action="{{ route('admin.support.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Archive this category?');">
												@csrf
												@method('DELETE')
												<button class="btn btn-sm btn-outline-danger radius-12 px-16" type="submit">Archive</button>
											</form>
										@endif
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="mt-24">
					{{ $categories->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

