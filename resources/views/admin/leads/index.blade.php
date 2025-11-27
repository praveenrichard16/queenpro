@extends('layouts.admin')

@section('title', 'Manage Leads')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Manage Leads</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Leads</li>
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
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="{{ request('search') }}" placeholder="Name, email, phone">
					</div>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Lead Source</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_source_id">
						<option value="">All Sources</option>
						@foreach($leadSources as $source)
							<option value="{{ $source->id }}" @selected(request('lead_source_id') == $source->id)>{{ $source->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Lead Stage</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_stage_id">
						<option value="">All Stages</option>
						@foreach($leadStages as $stage)
							<option value="{{ $stage->id }}" @selected(request('lead_stage_id') == $stage->id)>{{ $stage->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Assigned To Staff</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="assigned_to">
						<option value="">All Staff</option>
						@foreach($users as $user)
							<option value="{{ $user->id }}" @selected(request('assigned_to') == $user->id)>{{ $user->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-lg-12">
					<a href="{{ route('admin.leads.create') }}" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add Lead
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Source</th>
							<th>Stage</th>
							<th>Assigned To</th>
							<th>Expected Value</th>
							<th>Created</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($leads as $lead)
							<tr>
								<td>{{ $lead->name }}</td>
								<td>{{ $lead->email ?? '-' }}</td>
								<td>{{ $lead->phone ?? '-' }}</td>
								<td>
									@if($lead->source)
										<span class="badge bg-info">{{ $lead->source->name }}</span>
									@else
										<span class="text-secondary-light">-</span>
									@endif
								</td>
								<td>
									@if($lead->stage)
										<span class="badge bg-primary">{{ $lead->stage->name }}</span>
									@else
										<span class="text-secondary-light">-</span>
									@endif
								</td>
								<td>
									@if($lead->assignee)
										{{ $lead->assignee->name }}
									@else
										<span class="text-secondary-light">Unassigned</span>
									@endif
								</td>
								<td>
									@if($lead->expected_value)
										{{ currency($lead->expected_value) }}
									@else
										<span class="text-secondary-light">-</span>
									@endif
								</td>
								<td>{{ $lead->created_at?->format('d M Y H:i') }}</td>
								<td>
									<div class="d-flex align-items-center gap-2">
										<a href="{{ route('admin.leads.edit', $lead) }}" class="btn btn-sm btn-outline-primary" title="Edit">
											<iconify-icon icon="solar:pen-outline"></iconify-icon>
										</a>
										<form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
												<iconify-icon icon="solar:trash-bin-outline"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="9" class="text-center py-4 text-secondary-light">No leads found.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			@if($leads->hasPages())
				<div class="p-3">
					{{ $leads->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection
