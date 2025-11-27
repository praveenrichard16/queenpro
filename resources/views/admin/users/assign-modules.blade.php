@extends('layouts.admin')

@section('title', 'Assign Modules to Staff')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Assign Modules to Staff</h6>
			<p class="text-secondary-light mb-0">Manage module permissions for staff members.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.users.index') }}" class="hover-text-primary">Users</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Assign Modules</li>
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
			<form method="GET" action="{{ route('admin.users.assign-modules') }}" class="row gy-3 gx-3 align-items-end">
				<div class="col-xl-6 col-lg-8">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y">
							<iconify-icon icon="mage:search"></iconify-icon>
						</span>
						<input type="text" name="search" value="{{ $search ?? '' }}" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Search name or email">
					</div>
				</div>
				<div class="col-xl-3 col-lg-4 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.users.assign-modules') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th scope="col">Staff Member</th>
							<th scope="col">Contact</th>
							<th scope="col">Assigned Modules</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($staff as $user)
							<tr>
								<td>
									<div class="d-flex align-items-center gap-3">
										<x-avatar :user="$user" size="md" class="w-48-px h-48-px" />
										<div>
											<h6 class="text-md fw-semibold mb-4">{{ $user->name }}</h6>
											@if($user->designation)
												<span class="text-sm text-secondary-light">{{ $user->designation }}</span>
											@endif
										</div>
									</div>
								</td>
								<td>
									<div class="d-flex flex-column gap-1">
										<a href="mailto:{{ $user->email }}" class="text-decoration-none text-secondary-light">{{ $user->email }}</a>
										@if($user->phone)
											<a href="tel:{{ $user->phone }}" class="text-decoration-none text-secondary-light">{{ $user->phone }}</a>
										@endif
									</div>
								</td>
								<td>
									@if($user->modules->count() > 0)
										<div class="d-flex flex-wrap gap-1">
											@foreach($user->modules->take(5) as $module)
												<span class="badge bg-primary">{{ config("modules.modules.{$module->module_name}.name", $module->module_name) }}</span>
											@endforeach
											@if($user->modules->count() > 5)
												<span class="badge bg-secondary">+{{ $user->modules->count() - 5 }} more</span>
											@endif
										</div>
									@else
										<span class="text-sm text-secondary-light">No modules assigned</span>
									@endif
								</td>
								<td class="text-end">
									<button type="button" class="btn btn-primary btn-sm radius-12" data-bs-toggle="modal" data-bs-target="#assignModulesModal{{ $user->id }}">
										<iconify-icon icon="solar:settings-linear"></iconify-icon>
										Assign Modules
									</button>
								</td>
							</tr>

							<!-- Assign Modules Modal -->
							<div class="modal fade" id="assignModulesModal{{ $user->id }}" tabindex="-1" aria-labelledby="assignModulesModalLabel{{ $user->id }}" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<form method="POST" action="{{ route('admin.users.update-modules', $user) }}">
											@csrf
											<input type="hidden" name="from_assign_page" value="1">
											<div class="modal-header">
												<h5 class="modal-title" id="assignModulesModalLabel{{ $user->id }}">Assign Modules to {{ $user->name }}</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<p class="text-secondary-light mb-3">Select which modules this staff member can access:</p>
												<div class="row g-3">
													@foreach(config('modules.modules') as $moduleKey => $module)
														<div class="col-lg-6 col-md-6">
															<div class="form-check p-3 border radius-12">
																<input class="form-check-input" type="checkbox" name="modules[]" value="{{ $moduleKey }}" id="module_{{ $user->id }}_{{ $moduleKey }}" 
																	@checked(in_array($moduleKey, $user->assigned_modules))>
																<label class="form-check-label w-100" for="module_{{ $user->id }}_{{ $moduleKey }}">
																	<div class="d-flex align-items-center gap-2">
																		<iconify-icon icon="{{ $module['icon'] }}" class="text-lg"></iconify-icon>
																		<div>
																			<div class="fw-medium">{{ $module['name'] }}</div>
																			<div class="text-sm text-secondary-light">{{ $module['description'] }}</div>
																		</div>
																	</div>
																</label>
															</div>
														</div>
													@endforeach
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
												<button type="submit" class="btn btn-primary">Save Changes</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						@empty
							<tr>
								<td colspan="4" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No staff" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No staff members found</h6>
									<p class="text-secondary-light mb-0">Try adjusting your filters or add a new staff member.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($staff->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $staff->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

