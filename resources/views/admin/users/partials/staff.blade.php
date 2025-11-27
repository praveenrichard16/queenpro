<div class="tab-pane fade show active" id="user-pane-staff" role="tabpanel">
	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" action="{{ route('admin.users.index') }}" class="row gy-3 gx-3 align-items-end">
				<input type="hidden" name="tab" value="staff">
				<div class="col-xl-6 col-lg-8">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y">
							<iconify-icon icon="mage:search"></iconify-icon>
						</span>
						<input type="text" name="search" value="{{ $search ?? '' }}" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Search name, email or phone">
					</div>
				</div>
				<div class="col-xl-3 col-lg-4 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.users.index', ['tab' => 'staff']) }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-xl-3 col-lg-12 text-lg-end">
					<a href="{{ route('admin.users.create', ['role' => 'staff']) }}" class="btn btn-warning radius-12 text-white d-inline-flex align-items-center gap-2 h-56-px">
						<iconify-icon icon="solar:user-plus-linear"></iconify-icon>
						Add Staff
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
							<th scope="col">User</th>
							<th scope="col">Contact</th>
							<th scope="col">Modules</th>
							<th scope="col">Activities</th>
							<th scope="col">Last Activity</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($users ?? [] as $user)
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
											@foreach($user->modules->take(3) as $module)
												<span class="badge bg-primary">{{ config("modules.modules.{$module->module_name}.name", $module->module_name) }}</span>
											@endforeach
											@if($user->modules->count() > 3)
												<span class="badge bg-secondary">+{{ $user->modules->count() - 3 }} more</span>
											@endif
										</div>
									@else
										<span class="text-sm text-secondary-light">No modules assigned</span>
									@endif
								</td>
								<td>
									<span class="badge bg-info">{{ $user->activity_logs_count ?? 0 }}</span>
								</td>
								<td>
									@php
										$lastActivity = $user->activityLogs()->latest()->first();
									@endphp
									@if($lastActivity)
										<span class="text-sm text-secondary-light">{{ $lastActivity->created_at->diffForHumans() }}</span>
									@else
										<span class="text-sm text-secondary-light">Never</span>
									@endif
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.users.staff-activity', $user) }}" class="w-36-px h-36-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center" title="View Activity">
											<iconify-icon icon="solar:history-outline"></iconify-icon>
										</a>
										<a href="{{ route('admin.users.edit', $user) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Edit">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this staff member?')" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Delete">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No staff" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No staff found</h6>
									<p class="text-secondary-light mb-0">Try adjusting your filters or add a new staff member.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if(isset($users) && $users->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $users->links() }}
				</div>
			@endif
		</div>
	</div>
</div>

