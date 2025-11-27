@extends('layouts.admin')

@section('title', 'Tax Classes')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Tax Classes</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Tax Classes</li>
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
			<div class="d-flex justify-content-end">
				<a href="{{ route('admin.tax-classes.create') }}" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
					<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					Add Tax Class
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
							<th>Rate</th>
							<th>Description</th>
							<th>Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($taxClasses as $taxClass)
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium">{{ $taxClass->name }}</h6>
								</td>
								<td>
									<span class="fw-semibold">{{ number_format($taxClass->rate, 2) }}%</span>
								</td>
								<td>
									<p class="text-sm text-secondary-light mb-0">{{ $taxClass->description ?? '—' }}</p>
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $taxClass->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
										{{ $taxClass->is_active ? 'Active' : 'Inactive' }}
									</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.tax-classes.edit', $taxClass) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.tax-classes.destroy', $taxClass) }}" method="POST" onsubmit="return confirm('Delete this tax class?')" class="d-inline">
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
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No tax classes" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No tax classes found</h6>
									<p class="text-secondary-light mb-0">Create your first tax class to organize product taxes.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($taxClasses->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $taxClasses->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

