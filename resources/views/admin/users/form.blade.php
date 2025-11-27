@extends('layouts.admin')

@section('title', $user->exists ? 'Edit User' : 'Add User')

@section('content')
	<div class="dashboard-main-body">
		<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
			<h6 class="fw-semibold mb-0">{{ $user->exists ? 'Edit User' : 'Add User' }}</h6>
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
				<li class="fw-medium text-secondary-light">{{ $user->exists ? 'Edit' : 'Add' }}</li>
			</ul>
		</div>

		@php
			$isSuperAdmin = auth()->user()?->is_super_admin;
		@endphp

		<div class="card border-0">
			<div class="card-body p-24">
				<form method="POST" enctype="multipart/form-data" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="row g-4">
					@csrf
					@if($user->exists)
						@method('PUT')
					@endif

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Full Name</label>
						<input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
						@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Email</label>
						<input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('email') is-invalid @enderror" required>
						@error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Phone</label>
						<input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('phone') is-invalid @enderror" placeholder="+971 55 000 1234">
						@error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					@if($isSuperAdmin)
						<div class="col-lg-6">
							<label class="form-label text-secondary-light">Designation <span class="text-secondary-light">(optional)</span></label>
							<input type="text" name="designation" value="{{ old('designation', $user->designation) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('designation') is-invalid @enderror" placeholder="e.g. Sales Consultant">
							@error('designation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
						</div>
					@endif

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Password {{ $user->exists ? '(leave blank to keep current)' : '' }}</label>
						<input type="password" name="password" class="form-control bg-neutral-50 radius-12 h-56-px @error('password') is-invalid @enderror" {{ $user->exists ? '' : 'required' }}>
						@error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Confirm Password</label>
						<input type="password" name="password_confirmation" class="form-control bg-neutral-50 radius-12 h-56-px @error('password_confirmation') is-invalid @enderror" {{ $user->exists ? '' : 'required' }}>
						@error('password_confirmation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Avatar</label>
						<input type="file" name="avatar" class="form-control bg-neutral-50 radius-12 h-56-px @error('avatar') is-invalid @enderror">
						@error('avatar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
						<div class="form-text mt-1">Recommended size: 300 × 300px (JPG/PNG, max 2MB).</div>
					</div>

					@if($user->exists)
						<div class="col-lg-6">
							<label class="form-label text-secondary-light d-block">Current Avatar</label>
							<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex">
								<x-avatar :user="$user" size="xl" class="rounded" style="max-height:140px" />
							</div>
						</div>
					@endif

					@if($isSuperAdmin)
						<div class="col-lg-6">
							@php
								$roleValue = old('role', $user->is_admin ? 'admin' : ($user->is_staff ? 'staff' : 'customer'));
							@endphp
							<label class="form-label text-secondary-light d-block">Role</label>
							<select name="role" id="user_role" class="form-select bg-neutral-50 radius-12 h-56-px @error('role') is-invalid @enderror">
								<option value="customer" @selected($roleValue === 'customer')>Customer</option>
								<option value="staff" @selected($roleValue === 'staff')>Staff</option>
								<option value="admin" @selected($roleValue === 'admin')>Administrator</option>
							</select>
							@error('role') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
							<div class="form-text mt-1">Only super admins can create administrators or internal staff.</div>
						</div>
					@endif

					@if($isSuperAdmin)
						@php
							$showModules = false;
							if ($user->exists) {
								$showModules = $user->is_staff && !$user->is_admin;
							} else {
								$roleValue = old('role', request('role'));
								$showModules = $roleValue === 'staff';
							}
						@endphp
						<div class="col-12" id="modules_section" style="{{ !$showModules ? 'display:none;' : '' }}">
							<hr class="my-4">
							<h6 class="fw-semibold mb-16">Module Permissions</h6>
							<p class="text-secondary-light mb-3">Select which modules this user can access. Admins have access to all modules by default.</p>
							<div class="row g-3">
								@foreach(config('modules.modules') as $moduleKey => $module)
									<div class="col-lg-4 col-md-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="modules[]" value="{{ $moduleKey }}" id="module_{{ $moduleKey }}" 
												@checked($user->exists && in_array($moduleKey, $user->assigned_modules) && !$user->is_super_admin && !$user->is_admin)
												@disabled($user->exists && ($user->is_super_admin || $user->is_admin))>
											<label class="form-check-label" for="module_{{ $moduleKey }}">
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
							@if($user->exists && ($user->is_super_admin || $user->is_admin))
								<div class="alert alert-info mt-3">
									Admins and super admins have access to all modules automatically.
								</div>
							@endif
						</div>
					@endif

					<div class="col-12 d-flex gap-2 mt-8">
						<button class="btn btn-primary radius-12 px-24">{{ $user->exists ? 'Update User' : 'Create User' }}</button>
						<a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const roleSelect = document.getElementById('user_role');
		const modulesSection = document.getElementById('modules_section');
		
		if (roleSelect && modulesSection) {
			roleSelect.addEventListener('change', function() {
				if (this.value === 'staff' || this.value === 'admin') {
					modulesSection.style.display = 'block';
				} else {
					modulesSection.style.display = 'none';
				}
			});
		}
	});
</script>
@endpush

