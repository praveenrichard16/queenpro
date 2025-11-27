@extends('layouts.admin')

@section('title', 'Lead Source')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Lead Source</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Lead Source</li>
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
					<div class="nav flex-column nav-pills gap-2" id="lead-source-tabs" role="tablist" aria-orientation="vertical">
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ (request('tab') === 'add' || !request('tab')) ? 'active' : '' }}" id="lead-source-tab-add" data-bs-toggle="pill" data-bs-target="#lead-source-pane-add" type="button" role="tab" aria-controls="lead-source-pane-add" aria-selected="{{ (request('tab') === 'add' || !request('tab')) ? 'true' : 'false' }}">
							<span>Add Lead Source</span>
							<iconify-icon icon="solar:add-circle-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ request('tab') === 'manage' ? 'active' : '' }}" id="lead-source-tab-manage" data-bs-toggle="pill" data-bs-target="#lead-source-pane-manage" type="button" role="tab" aria-controls="lead-source-pane-manage" aria-selected="{{ request('tab') === 'manage' ? 'true' : 'false' }}">
							<span>Manage Lead Source</span>
							<iconify-icon icon="solar:list-check-linear" class="text-lg"></iconify-icon>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="tab-content" id="lead-source-tabs-content">
				<!-- Add Lead Source Tab -->
				<div class="tab-pane fade {{ (request('tab') === 'add' || !request('tab')) ? 'show active' : '' }}" id="lead-source-pane-add" role="tabpanel" aria-labelledby="lead-source-tab-add">
					<div class="card border-0">
						<div class="card-body p-24">
							<form method="POST" action="{{ route('admin.lead-sources.store') }}" class="row g-4">
								@csrf
								<input type="hidden" name="tab" value="add">

								<div class="col-12">
									<h6 class="fw-semibold mb-16">Lead Source Information</h6>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
									<input type="text" name="name" value="{{ old('name') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
									@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Slug</label>
									<input type="text" name="slug" value="{{ old('slug') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('slug') is-invalid @enderror" placeholder="Auto-generated if left empty">
									@error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
									<div class="form-text mt-1">Leave empty to auto-generate from name</div>
								</div>

								<div class="col-12">
									<label class="form-label text-secondary-light">Description</label>
									<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
									@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
								</div>

								<div class="col-12">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', true))>
										<label class="form-check-label" for="is_active">Active</label>
									</div>
								</div>

								<div class="col-12">
									<div class="d-flex gap-2">
										<button type="submit" class="btn btn-primary radius-12 h-56-px">Create Lead Source</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Manage Lead Source Tab -->
				<div class="tab-pane fade {{ request('tab') === 'manage' ? 'show active' : '' }}" id="lead-source-pane-manage" role="tabpanel" aria-labelledby="lead-source-tab-manage">
					<div class="card border-0">
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Name</th>
											<th>Slug</th>
											<th>Status</th>
											<th class="text-end">Leads</th>
											<th class="text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
										@forelse($leadSources as $leadSource)
											<tr>
												<td>
													<h6 class="text-md mb-2 fw-medium">{{ $leadSource->name }}</h6>
													@if($leadSource->description)
														<p class="text-sm text-secondary-light mb-0">{{ \Illuminate\Support\Str::limit($leadSource->description, 50) }}</p>
													@endif
												</td>
												<td>{{ $leadSource->slug }}</td>
												<td>
													<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $leadSource->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
														{{ $leadSource->is_active ? 'Active' : 'Inactive' }}
													</span>
												</td>
												<td class="text-end">{{ $leadSource->leads_count }}</td>
												<td class="text-end">
													<div class="d-inline-flex gap-2">
														<a href="{{ route('admin.lead-sources.edit', $leadSource) }}?tab=manage" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
															<iconify-icon icon="lucide:edit"></iconify-icon>
														</a>
														<form action="{{ route('admin.lead-sources.destroy', $leadSource) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead source?');" class="d-inline">
															@csrf
															@method('DELETE')
															<input type="hidden" name="tab" value="manage">
															<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0">
																<iconify-icon icon="lucide:trash-2"></iconify-icon>
															</button>
														</form>
													</div>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="5" class="text-center py-4 text-secondary-light">No lead sources found.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
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
	// Preserve tab state on form submissions
	document.querySelectorAll('form').forEach(form => {
		form.addEventListener('submit', function() {
			const activeTab = document.querySelector('.nav-pills .active');
			if (activeTab) {
				const tabId = activeTab.id.replace('lead-source-tab-', '');
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

