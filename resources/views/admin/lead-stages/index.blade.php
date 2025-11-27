@extends('layouts.admin')

@section('title', 'Manage Lead Stages')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Manage Lead Stages</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Lead Stages</li>
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
				<h6 class="mb-2">Manage lead stages</h6>
				<p class="mb-0 text-secondary-light">Define the stages in your lead pipeline (e.g., New, Contacted, Qualified, Won, Lost).</p>
			</div>
			<a href="{{ route('admin.lead-stages.create') }}" class="btn btn-primary radius-12 d-inline-flex align-items-center gap-2">
				<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
				Add Lead Stage
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
							<th>Slug</th>
							<th>Sort Order</th>
							<th>Flags</th>
							<th class="text-end">Leads</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($leadStages as $leadStage)
							<tr>
								<td>
									<h6 class="text-md mb-2 fw-medium">{{ $leadStage->name }}</h6>
									<p class="text-sm text-secondary-light mb-0">{{ $leadStage->slug }}</p>
								</td>
								<td>{{ $leadStage->slug }}</td>
								<td>{{ $leadStage->sort_order }}</td>
								<td>
									<div class="d-flex gap-2">
										@if($leadStage->is_default)
											<span class="badge bg-primary">Default</span>
										@endif
										@if($leadStage->is_won)
											<span class="badge bg-success">Won</span>
										@endif
										@if($leadStage->is_lost)
											<span class="badge bg-danger">Lost</span>
										@endif
									</div>
								</td>
								<td class="text-end">{{ $leadStage->leads_count }}</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.lead-stages.edit', $leadStage) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.lead-stages.destroy', $leadStage) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead stage?');" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0">
												<iconify-icon icon="lucide:trash-2"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center py-4 text-secondary-light">No lead stages found.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

