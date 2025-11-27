@extends('layouts.admin')

@section('title', 'Lead Management')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Lead Management</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Lead Management</li>
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

	@if($errors->any())
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please review the highlighted fields below.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="row">
		<div class="col-lg-3 mb-24">
			<div class="card border-0 h-100">
				<div class="card-body p-16">
					<div class="nav flex-column nav-pills gap-2" id="lead-tabs" role="tablist" aria-orientation="vertical">
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ (request('tab') === 'add' || !request('tab')) ? 'active' : '' }}" id="lead-tab-add" data-bs-toggle="pill" data-bs-target="#lead-pane-add" type="button" role="tab" aria-controls="lead-pane-add" aria-selected="{{ (request('tab') === 'add' || !request('tab')) ? 'true' : 'false' }}">
							<span>Add Leads</span>
							<iconify-icon icon="solar:add-circle-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ request('tab') === 'manage' ? 'active' : '' }}" id="lead-tab-manage" data-bs-toggle="pill" data-bs-target="#lead-pane-manage" type="button" role="tab" aria-controls="lead-pane-manage" aria-selected="{{ request('tab') === 'manage' ? 'true' : 'false' }}">
							<span>Manage Leads</span>
							<iconify-icon icon="solar:list-check-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ request('tab') === 'assign' ? 'active' : '' }}" id="lead-tab-assign" data-bs-toggle="pill" data-bs-target="#lead-pane-assign" type="button" role="tab" aria-controls="lead-pane-assign" aria-selected="{{ request('tab') === 'assign' ? 'true' : 'false' }}">
							<span>Assign Leads</span>
							<iconify-icon icon="solar:user-check-rounded-outline" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ request('tab') === 'next-followups' ? 'active' : '' }}" id="lead-tab-next" data-bs-toggle="pill" data-bs-target="#lead-pane-next" type="button" role="tab" aria-controls="lead-pane-next" aria-selected="{{ request('tab') === 'next-followups' ? 'true' : 'false' }}">
							<span>Next Followups</span>
							<iconify-icon icon="solar:calendar-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ request('tab') === 'today-followups' ? 'active' : '' }}" id="lead-tab-today" data-bs-toggle="pill" data-bs-target="#lead-pane-today" type="button" role="tab" aria-controls="lead-pane-today" aria-selected="{{ request('tab') === 'today-followups' ? 'true' : 'false' }}">
							<span>Today's Followups</span>
							<iconify-icon icon="solar:alarm-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ request('tab') === 'analytics' ? 'active' : '' }}" id="lead-tab-analytics" data-bs-toggle="pill" data-bs-target="#lead-pane-analytics" type="button" role="tab" aria-controls="lead-pane-analytics" aria-selected="{{ request('tab') === 'analytics' ? 'true' : 'false' }}">
							<span>Lead Analytics</span>
							<iconify-icon icon="solar:chart-square-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ request('tab') === 'trash' ? 'active' : '' }}" id="lead-tab-trash" data-bs-toggle="pill" data-bs-target="#lead-pane-trash" type="button" role="tab" aria-controls="lead-pane-trash" aria-selected="{{ request('tab') === 'trash' ? 'true' : 'false' }}">
							<span>Trash</span>
							<iconify-icon icon="solar:trash-bin-trash-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ request('tab') === 'import-export' ? 'active' : '' }}" id="lead-tab-import" data-bs-toggle="pill" data-bs-target="#lead-pane-import" type="button" role="tab" aria-controls="lead-pane-import" aria-selected="{{ request('tab') === 'import-export' ? 'true' : 'false' }}">
							<span>Import / Export</span>
							<iconify-icon icon="solar:cloud-upload-linear" class="text-lg"></iconify-icon>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="tab-content" id="lead-tabs-content">
				<!-- Add Leads Tab -->
				<div class="tab-pane fade {{ (request('tab') === 'add' || !request('tab')) ? 'show active' : '' }}" id="lead-pane-add" role="tabpanel" aria-labelledby="lead-tab-add">
					<div class="card border-0">
						<div class="card-body p-24">
							<form method="POST" action="{{ route('admin.leads.store') }}" class="row g-4">
								@csrf
								<input type="hidden" name="tab" value="add">

								<div class="col-12">
									<h6 class="fw-semibold mb-16">Basic Information</h6>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
									<input type="text" name="name" value="{{ old('name') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
									@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Email</label>
									<input type="email" name="email" value="{{ old('email') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('email') is-invalid @enderror">
									@error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Phone</label>
									<input type="text" name="phone" value="{{ old('phone') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('phone') is-invalid @enderror">
									@error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Customer</label>
									<select name="user_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('user_id') is-invalid @enderror">
										<option value="">Select Customer</option>
										@foreach($users as $user)
											<option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
										@endforeach
									</select>
									@error('user_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-12">
									<hr class="my-4">
									<h6 class="fw-semibold mb-16">Lead Details</h6>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Lead Source</label>
									<select name="lead_source_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('lead_source_id') is-invalid @enderror">
										<option value="">Select Source</option>
										@foreach($leadSources as $source)
											<option value="{{ $source->id }}" @selected(old('lead_source_id') == $source->id)>{{ $source->name }}</option>
										@endforeach
									</select>
									@error('lead_source_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Lead Stage</label>
									<select name="lead_stage_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('lead_stage_id') is-invalid @enderror">
										<option value="">Select Stage</option>
										@foreach($leadStages as $stage)
											<option value="{{ $stage->id }}" @selected(old('lead_stage_id') == $stage->id)>{{ $stage->name }}</option>
										@endforeach
									</select>
									@error('lead_stage_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Expected Value</label>
									<input type="number" name="expected_value" value="{{ old('expected_value') }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('expected_value') is-invalid @enderror">
									@error('expected_value') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Assign To Staff</label>
									<select name="assigned_to" class="form-select bg-neutral-50 radius-12 h-56-px @error('assigned_to') is-invalid @enderror">
										<option value="">Unassigned</option>
										@foreach($users as $user)
											<option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>{{ $user->name }}</option>
										@endforeach
									</select>
									<div class="form-text mt-1">Leads can only be assigned to staff members.</div>
									@error('assigned_to') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Followup Planning</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Next Followup Date</label>
					<input type="date" name="next_followup_date" value="{{ old('next_followup_date') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('next_followup_date') is-invalid @enderror">
					@error('next_followup_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Next Followup Time</label>
					<input type="time" name="next_followup_time" value="{{ old('next_followup_time') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('next_followup_time') is-invalid @enderror">
					@error('next_followup_time') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Lead Score</label>
					<input type="number" name="lead_score" value="{{ old('lead_score', 0) }}" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('lead_score') is-invalid @enderror">
					@error('lead_score') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

								<div class="col-12">
									<label class="form-label text-secondary-light">Notes</label>
									<textarea name="notes" rows="4" class="form-control bg-neutral-50 radius-12 @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
									@error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-12">
									<div class="d-flex gap-2">
										<button type="submit" class="btn btn-primary radius-12 h-56-px">Create Lead</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Manage Leads Tab -->
				<div class="tab-pane fade {{ request('tab') === 'manage' ? 'show active' : '' }}" id="lead-pane-manage" role="tabpanel" aria-labelledby="lead-tab-manage">
					<div class="card border-0 shadow-none bg-base mb-24">
						<div class="card-body p-24">
							<form method="GET" class="row g-3 align-items-end">
								<input type="hidden" name="tab" value="manage">
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
									<a href="{{ route('admin.leads.index', ['tab' => 'manage']) }}" class="btn btn-outline-secondary h-56-px radius-12">
										<iconify-icon icon="mi:refresh"></iconify-icon>
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
											<th>Next Followup</th>
											<th>Score</th>
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
												<td>
													@if($lead->next_followup_date)
														<span class="badge bg-secondary">{{ $lead->next_followup_date?->format('d M Y') }} {{ $lead->next_followup_time }}</span>
													@else
														<span class="text-secondary-light">Not scheduled</span>
													@endif
												</td>
												<td>
													<span class="badge {{ $lead->lead_score >= 70 ? 'bg-success' : ($lead->lead_score >= 40 ? 'bg-warning' : 'bg-danger') }}">
														{{ $lead->lead_score ?? 0 }}
													</span>
												</td>
												<td>{{ $lead->created_at?->format('d M Y H:i') }}</td>
												<td>
													<div class="d-flex align-items-center gap-2">
														<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#leadTools{{ $lead->id }}" aria-expanded="false" aria-controls="leadTools{{ $lead->id }}">
															Tools
														</button>
														<a href="{{ route('admin.leads.edit', $lead) }}?tab=manage" class="btn btn-sm btn-outline-primary" title="Edit">
															<iconify-icon icon="solar:pen-outline"></iconify-icon>
														</a>
														<form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');">
															@csrf
															@method('DELETE')
															<input type="hidden" name="tab" value="manage">
															<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
																<iconify-icon icon="solar:trash-bin-outline"></iconify-icon>
															</button>
														</form>
													</div>
												</td>
											</tr>
											<tr class="collapse" id="leadTools{{ $lead->id }}">
												<td colspan="11" class="bg-neutral-50">
													<div class="row g-4">
														<div class="col-lg-6">
															<h6 class="fw-semibold mb-12">Schedule Followup</h6>
															@include('admin.leads.partials.followup-form', ['lead' => $lead])
														</div>
														<div class="col-lg-6">
															<h6 class="fw-semibold mb-12">Recent Activity</h6>
															@include('admin.leads.partials.activity-log', ['activities' => $lead->activities ?? collect()])
														</div>
													</div>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="11" class="text-center py-4 text-secondary-light">No leads found.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>
							@if(isset($leads) && $leads->hasPages())
								<div class="p-3">
									{{ $leads->appends(['tab' => 'manage'])->links() }}
								</div>
							@endif
						</div>
					</div>
				</div>

				<!-- Assign Leads Tab -->
				<div class="tab-pane fade {{ request('tab') === 'assign' ? 'show active' : '' }}" id="lead-pane-assign" role="tabpanel" aria-labelledby="lead-tab-assign">
					<div class="card border-0 shadow-none bg-base mb-24">
						<div class="card-body p-24">
							<form method="GET" class="row g-3 align-items-end">
								<input type="hidden" name="tab" value="assign">
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
									<a href="{{ route('admin.leads.index', ['tab' => 'assign']) }}" class="btn btn-outline-secondary h-56-px radius-12">
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
								<input type="hidden" name="tab" value="assign">
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
											@forelse($tab === 'assign' ? $assignLeads : $leads as $lead)
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
								@if($tab === 'assign' && isset($assignLeads) && $assignLeads->hasPages())
									<div class="mt-3">
										{{ $assignLeads->appends(['tab' => 'assign'])->links() }}
									</div>
								@elseif(isset($leads) && $leads->hasPages())
									<div class="mt-3">
										{{ $leads->appends(['tab' => 'assign'])->links() }}
									</div>
								@endif
							</form>
						</div>
					</div>
				</div>

				<div class="tab-pane fade {{ request('tab') === 'next-followups' ? 'show active' : '' }}" id="lead-pane-next" role="tabpanel" aria-labelledby="lead-tab-next">
					<div class="card border-0">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-16">Upcoming Followups</h6>
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Lead</th>
											<th>Stage</th>
											<th>Assigned To</th>
											<th>Scheduled For</th>
											<th>Notes</th>
											<th class="text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
										@forelse($upcomingFollowups as $followup)
											<tr>
												<td>
													<div class="d-flex flex-column">
														<span class="fw-semibold">{{ $followup->lead?->name }}</span>
														<small class="text-secondary">{{ $followup->lead?->email }}</small>
													</div>
												</td>
												<td><span class="badge bg-primary">{{ $followup->lead?->stage?->name ?? 'N/A' }}</span></td>
												<td>{{ $followup->lead?->assignee?->name ?? 'Unassigned' }}</td>
												<td>
													<strong>{{ $followup->followup_date?->format('d M Y') }}</strong>
													@if($followup->followup_time)
														<div class="text-secondary">{{ $followup->followup_time }}</div>
													@endif
												</td>
												<td>{{ \Illuminate\Support\Str::limit($followup->notes, 60) ?: '-' }}</td>
												<td class="text-end">
													<div class="d-flex justify-content-end gap-2">
														<form method="POST" action="{{ route('admin.leads.followups.complete', [$followup->lead_id, $followup->id]) }}">
															@csrf
															<button class="btn btn-sm btn-outline-success">Complete</button>
														</form>
														<form method="POST" action="{{ route('admin.leads.followups.cancel', [$followup->lead_id, $followup->id]) }}">
															@csrf
															<input type="hidden" name="reason" value="Cancelled from pipeline">
															<button class="btn btn-sm btn-outline-warning">Cancel</button>
														</form>
														<form method="POST" action="{{ route('admin.leads.followups.destroy', [$followup->lead_id, $followup->id]) }}" onsubmit="return confirm('Remove this followup?');">
															@csrf
															@method('DELETE')
															<button class="btn btn-sm btn-outline-danger">Delete</button>
														</form>
													</div>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="6" class="text-center text-secondary-light py-4">No upcoming followups scheduled.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>
							@if($upcomingFollowups->hasPages())
								<div class="pt-3">
									{{ $upcomingFollowups->appends(['tab' => 'next-followups'])->links() }}
								</div>
							@endif
						</div>
					</div>
				</div>

				<div class="tab-pane fade {{ request('tab') === 'today-followups' ? 'show active' : '' }}" id="lead-pane-today" role="tabpanel" aria-labelledby="lead-tab-today">
					<div class="card border-0">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-16">Today's Followups</h6>
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Lead</th>
											<th>Stage</th>
											<th>Assigned To</th>
											<th>Time</th>
											<th>Notes</th>
											<th class="text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
										@forelse($todayFollowups as $followup)
											<tr>
												<td>{{ $followup->lead?->name }}</td>
												<td><span class="badge bg-info">{{ $followup->lead?->stage?->name ?? 'N/A' }}</span></td>
												<td>{{ $followup->lead?->assignee?->name ?? 'Unassigned' }}</td>
												<td>{{ $followup->followup_time ?? 'Anytime' }}</td>
												<td>{{ \Illuminate\Support\Str::limit($followup->notes, 60) ?: '-' }}</td>
												<td class="text-end">
													<form method="POST" action="{{ route('admin.leads.followups.complete', [$followup->lead_id, $followup->id]) }}" class="d-inline">
														@csrf
														<button class="btn btn-sm btn-outline-success">Mark Done</button>
													</form>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="6" class="text-center text-secondary-light py-4">No followups scheduled for today.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>
							@if($todayFollowups->hasPages())
								<div class="pt-3">
									{{ $todayFollowups->appends(['tab' => 'today-followups'])->links() }}
								</div>
							@endif
						</div>
					</div>
				</div>

				<div class="tab-pane fade {{ request('tab') === 'analytics' ? 'show active' : '' }}" id="lead-pane-analytics" role="tabpanel" aria-labelledby="lead-tab-analytics">
					<div class="row g-3 mb-24">
						<div class="col-md-3">
							<div class="card border-0 shadow-sm h-100">
								<div class="card-body">
									<p class="text-secondary-light mb-1">Total Leads</p>
									<h4 class="fw-semibold mb-0">{{ $analytics['total_leads'] ?? 0 }}</h4>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card border-0 shadow-sm h-100">
								<div class="card-body">
									<p class="text-secondary-light mb-1">Won Leads</p>
									<h4 class="fw-semibold mb-0 text-success">{{ $analytics['won_leads'] ?? 0 }}</h4>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card border-0 shadow-sm h-100">
								<div class="card-body">
									<p class="text-secondary-light mb-1">Lost Leads</p>
									<h4 class="fw-semibold mb-0 text-danger">{{ $analytics['lost_leads'] ?? 0 }}</h4>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card border-0 shadow-sm h-100">
								<div class="card-body">
									<p class="text-secondary-light mb-1">Conversion Rate</p>
									<h4 class="fw-semibold mb-0">{{ $analytics['conversion_rate'] ?? 0 }}%</h4>
								</div>
							</div>
						</div>
					</div>
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-3">Leads by Stage</h6>
							<div class="table-responsive">
								<table class="table mb-0">
									<thead>
										<tr>
											<th>Stage</th>
											<th>Status</th>
											<th class="text-end">Count</th>
										</tr>
									</thead>
									<tbody>
										@foreach($analytics['stage_stats'] ?? [] as $stage)
											<tr>
												<td>{{ $stage->name }}</td>
												<td>
													@if($stage->is_won)
														<span class="badge bg-success">Won</span>
													@elseif($stage->is_lost)
														<span class="badge bg-danger">Lost</span>
													@else
														<span class="badge bg-secondary">Open</span>
													@endif
												</td>
												<td class="text-end">{{ $stage->leads_count ?? 0 }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="card border-0">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-3">Leads by Source</h6>
							<div class="row g-3">
								@foreach($analytics['source_stats'] ?? [] as $source)
									<div class="col-md-3">
										<div class="border rounded-3 p-3 h-100">
											<p class="text-secondary-light mb-1">{{ $source->name }}</p>
											<h5 class="fw-semibold mb-0">{{ $source->leads_count ?? 0 }}</h5>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade {{ request('tab') === 'trash' ? 'show active' : '' }}" id="lead-pane-trash" role="tabpanel" aria-labelledby="lead-tab-trash">
					<div class="card border-0">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-16">Trashed Leads</h6>
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>Stage</th>
											<th>Deleted At</th>
											<th class="text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
										@forelse($trashedLeads as $lead)
											<tr>
												<td>{{ $lead->name }}</td>
												<td>{{ $lead->email ?? '-' }}</td>
												<td>{{ $lead->stage?->name ?? '-' }}</td>
												<td>{{ $lead->deleted_at?->format('d M Y H:i') }}</td>
												<td class="text-end">
													<form method="POST" action="{{ route('admin.leads.restore', $lead->id) }}" class="d-inline">
														@csrf
														<button class="btn btn-sm btn-outline-success">Restore</button>
													</form>
													<form method="POST" action="{{ route('admin.leads.force-delete', $lead->id) }}" class="d-inline" onsubmit="return confirm('Permanently delete this lead?');">
														@csrf
														@method('DELETE')
														<button class="btn btn-sm btn-outline-danger">Delete</button>
													</form>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="5" class="text-center text-secondary-light py-4">Trash is empty.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>
							@if($trashedLeads->hasPages())
								<div class="pt-3">
									{{ $trashedLeads->appends(['tab' => 'trash'])->links() }}
								</div>
							@endif
						</div>
					</div>
				</div>

				<div class="tab-pane fade {{ request('tab') === 'import-export' ? 'show active' : '' }}" id="lead-pane-import" role="tabpanel" aria-labelledby="lead-tab-import">
					<div class="row g-3">
						<div class="col-lg-6">
							<div class="card border-0 h-100">
								<div class="card-body p-24">
									<h6 class="fw-semibold mb-3">Import Leads</h6>
									<form method="POST" action="{{ route('admin.leads.import') }}" enctype="multipart/form-data" class="row g-3">
										@csrf
										<div class="col-12">
											<label class="form-label text-secondary-light">CSV File</label>
											<input type="file" name="file" accept=".csv" class="form-control" required>
											<small class="text-secondary-light d-block mt-1">Download the exported template, update rows, then upload to import.</small>
										</div>
										<div class="col-12">
											<label class="form-label text-secondary-light">Mode</label>
											<select name="mode" class="form-select">
												<option value="create">Create only (skip existing emails)</option>
												<option value="update">Update existing leads by email</option>
												<option value="replace">Replace (soft delete all leads before importing)</option>
											</select>
										</div>
										<div class="col-12">
											<button class="btn btn-primary w-100">Upload &amp; Process</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="card border-0 h-100">
								<div class="card-body p-24">
									<h6 class="fw-semibold mb-3">Export Leads</h6>
									<form method="GET" action="{{ route('admin.leads.export') }}" class="row g-3">
										<div class="col-md-6">
											<label class="form-label text-secondary-light">Lead Stage</label>
											<select name="lead_stage_id" class="form-select">
												<option value="">All</option>
												@foreach($leadStages as $stage)
													<option value="{{ $stage->id }}">{{ $stage->name }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label text-secondary-light">Lead Source</label>
											<select name="lead_source_id" class="form-select">
												<option value="">All</option>
												@foreach($leadSources as $source)
													<option value="{{ $source->id }}">{{ $source->name }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label text-secondary-light">Assigned Staff</label>
											<select name="assigned_to" class="form-select">
												<option value="">All</option>
												@foreach($users as $user)
													<option value="{{ $user->id }}">{{ $user->name }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label text-secondary-light">Format</label>
											<select name="format" class="form-select">
												<option value="csv">CSV</option>
											</select>
										</div>
										<div class="col-12">
											<button class="btn btn-outline-primary w-100">Download Export</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script>
	// Handle select all checkbox for assign tab
	const selectAllCheckbox = document.getElementById('selectAll');
	if (selectAllCheckbox) {
		selectAllCheckbox.addEventListener('change', function() {
			const checkboxes = document.querySelectorAll('.lead-checkbox');
			checkboxes.forEach(checkbox => {
				checkbox.checked = this.checked;
			});
		});
	}

	// Handle assign form submission
	const assignForm = document.getElementById('assignForm');
	if (assignForm) {
		assignForm.addEventListener('submit', function(e) {
			const checked = document.querySelectorAll('.lead-checkbox:checked');
			if (checked.length === 0) {
				e.preventDefault();
				alert('Please select at least one lead to assign.');
				return false;
			}
		});
	}

	// Preserve tab state on form submissions
	document.querySelectorAll('form').forEach(form => {
		form.addEventListener('submit', function() {
			const activeTab = document.querySelector('.nav-pills .active');
			if (activeTab) {
				const tabId = activeTab.id.replace('lead-tab-', '');
				const hiddenInput = document.createElement('input');
				hiddenInput.type = 'hidden';
				hiddenInput.name = 'tab';
				hiddenInput.value = tabId;
				form.appendChild(hiddenInput);
			}
		});
	});
</script>
@endpush
@endsection

