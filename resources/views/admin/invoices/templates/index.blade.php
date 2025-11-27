@extends('layouts.admin')

@section('title', 'Invoice Templates')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Invoice Templates</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Invoice Templates</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<div class="d-flex justify-content-end">
				<a href="{{ route('admin.invoices.templates.create') }}" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
					<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					Add Template
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
							<th>Name</th>
							<th>Default</th>
							<th>Status</th>
							<th>Created</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($templates as $template)
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium">{{ $template->name }}</h6>
								</td>
								<td>
									@if($template->is_default)
										<span class="badge bg-primary">Default</span>
									@else
										<span class="text-secondary-light">—</span>
									@endif
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $template->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
										{{ $template->is_active ? 'Active' : 'Inactive' }}
									</span>
								</td>
								<td>
									<span class="text-sm text-secondary-light">{{ $template->created_at->format('M d, Y') }}</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.invoices.templates.edit', $template) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.invoices.templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Delete this template?')" class="d-inline">
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
								<td colspan="5" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No templates" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No templates found</h6>
									<p class="text-secondary-light mb-0">Create your first invoice template.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($templates->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $templates->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

