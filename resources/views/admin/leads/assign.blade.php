@extends('layouts.admin')

@section('title', 'Assign Leads')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Assign Leads</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.leads.index') }}" class="hover-text-primary">Leads</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Assign</li>
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
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Lead Source</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_source_id">
						<option value="">All Sources</option>
						@foreach($leadSources as $source)
							<option value="{{ $source->id }}" @selected(request('lead_source_id') == $source->id)>{{ $source->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Lead Stage</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_stage_id">
						<option value="">All Stages</option>
						@foreach($leadStages as $stage)
							<option value="{{ $stage->id }}" @selected(request('lead_stage_id') == $stage->id)>{{ $stage->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Filter</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="filter">
						<option value="">All Leads</option>
						<option value="unassigned" @selected(request('filter') === 'unassigned')>Unassigned Only</option>
					</select>
				</div>
				<div class="col-lg-4 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.leads.assign') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.leads.assign.update') }}" id="assignForm">
				@csrf
				<div class="row g-3 mb-4">
					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Assign Selected Leads To Staff <span class="text-danger">*</span></label>
						<select name="assigned_to" class="form-select bg-neutral-50 radius-12 h-56-px" required>
							<option value="">Select Staff Member</option>
							@foreach($users as $user)
								<option value="{{ $user->id }}">{{ $user->name }}</option>
							@endforeach
						</select>
						<div class="form-text mt-1">Leads can only be assigned to staff members.</div>
					</div>
					<div class="col-lg-6 d-flex align-items-end">
						<button type="submit" class="btn btn-primary radius-12 h-56-px">Assign Selected Leads</button>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table bordered-table mb-0 align-middle">
						<thead>
							<tr>
								<th width="50">
									<input type="checkbox" id="selectAll" class="form-check-input">
								</th>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Source</th>
								<th>Stage</th>
								<th>Currently Assigned To</th>
								<th>Created</th>
							</tr>
						</thead>
						<tbody>
							@forelse($leads as $lead)
								<tr>
									<td>
										<input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" class="form-check-input lead-checkbox">
									</td>
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
									<td>{{ $lead->created_at?->format('d M Y H:i') }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="8" class="text-center py-4 text-secondary-light">No leads found.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				@if($leads->hasPages())
					<div class="mt-3">
						{{ $leads->links() }}
					</div>
				@endif
			</form>
		</div>
	</div>
</div>

@push('scripts')
<script>
	document.getElementById('selectAll').addEventListener('change', function() {
		const checkboxes = document.querySelectorAll('.lead-checkbox');
		checkboxes.forEach(checkbox => {
			checkbox.checked = this.checked;
		});
	});

	document.getElementById('assignForm').addEventListener('submit', function(e) {
		const checked = document.querySelectorAll('.lead-checkbox:checked');
		if (checked.length === 0) {
			e.preventDefault();
			alert('Please select at least one lead to assign.');
			return false;
		}
	});
</script>
@endpush
@endsection

