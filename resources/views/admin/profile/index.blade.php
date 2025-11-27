@extends('layouts.admin')

@section('title', 'Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/quill.snow.css') }}">
@endpush

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Profile</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Profile</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif
	@if($errors->any())
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please fix the errors below and try again.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="row gy-4">
		<div class="col-xl-4">
			<div class="user-grid-card border radius-16 overflow-hidden bg-base h-100">
				<div class="position-relative">
					<img src="{{ asset('wowdash/assets/images/user-grid/user-grid-bg1.png') }}" alt="" class="w-100 object-fit-cover">
					<div class="position-absolute start-50 translate-middle-x mt-24" style="top: 100%;">
						<x-avatar :user="$user" size="2xl" class="border border-white border-width-2-px w-160-px h-160-px" />
					</div>
				</div>
				<div class="pt-120 pb-24 px-24 text-center">
					<h6 class="mb-0">{{ $user->name }}</h6>
					<span class="text-secondary-light">{{ $user->designation ?? 'Administrator' }}</span>
				</div>
				<div class="px-24 pb-24">
					<h6 class="text-xl mb-16">Personal Info</h6>
					<ul class="list-unstyled mb-0">
						<li class="d-flex align-items-center gap-1 mb-12">
							<span class="w-30 text-md fw-semibold text-primary-light">Email</span>
							<span class="w-70 text-secondary-light fw-medium">: {{ $user->email }}</span>
						</li>
						<li class="d-flex align-items-center gap-1 mb-12">
							<span class="w-30 text-md fw-semibold text-primary-light">Phone</span>
							<span class="w-70 text-secondary-light fw-medium">: {{ $user->phone ?? 'Not set' }}</span>
						</li>
						<li class="d-flex align-items-center gap-1 mb-0">
							<span class="w-30 text-md fw-semibold text-primary-light">Designation</span>
							<span class="w-70 text-secondary-light fw-medium">: {{ $user->designation ?? 'Not set' }}</span>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-xl-8">
			<div class="card h-100">
				<div class="card-body p-24">
					<ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="profile-tab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link d-flex align-items-center px-24 active" id="tab-edit-profile" data-bs-toggle="pill" data-bs-target="#pane-edit-profile" type="button" role="tab" aria-controls="pane-edit-profile" aria-selected="true">
								Edit Profile
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link d-flex align-items-center px-24" id="tab-change-password" data-bs-toggle="pill" data-bs-target="#pane-change-password" type="button" role="tab" aria-controls="pane-change-password" aria-selected="false">
								Change Password
							</button>
						</li>
					</ul>

					<div class="tab-content" id="profile-tabContent">
						<div class="tab-pane fade show active" id="pane-edit-profile" role="tabpanel" aria-labelledby="tab-edit-profile" tabindex="0">
							<h6 class="text-md text-primary-light mb-16">Profile Image</h6>
							<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="mb-24">
								@csrf
								<div class="row g-4">
									<div class="col-md-6">
										<label class="form-label fw-semibold text-primary-light text-sm mb-8">Full Name <span class="text-danger-600">*</span></label>
										<input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control radius-8 @error('name') is-invalid @enderror" placeholder="Enter full name" required>
										@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
									</div>
									<div class="col-md-6">
										<label class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span class="text-danger-600">*</span></label>
										<input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control radius-8 @error('email') is-invalid @enderror" placeholder="Enter email address" required>
										@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
									</div>
									<div class="col-md-6">
										<label class="form-label fw-semibold text-primary-light text-sm mb-8">Phone</label>
										<input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control radius-8 @error('phone') is-invalid @enderror" placeholder="Enter phone number">
										@error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
									</div>
									<div class="col-md-6">
										<label class="form-label fw-semibold text-primary-light text-sm mb-8">Designation</label>
										<input type="text" name="designation" value="{{ old('designation', $user->designation) }}" class="form-control radius-8 @error('designation') is-invalid @enderror" placeholder="Enter designation">
										@error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
									</div>
									<div class="col-md-12">
										<label class="form-label fw-semibold text-primary-light text-sm mb-8">Avatar</label>
										<input type="file" name="avatar" class="form-control radius-8 @error('avatar') is-invalid @enderror" accept="image/*">
										@error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
										<div class="form-text">Recommended size: 240×240 px (PNG or JPG).</div>
									</div>
								</div>
								<div class="d-flex align-items-center gap-3 mt-2">
									<button class="btn btn-primary radius-12 px-24">Save Changes</button>
								</div>
							</form>
						</div>

						<div class="tab-pane fade" id="pane-change-password" role="tabpanel" aria-labelledby="tab-change-password" tabindex="0">
							<h6 class="text-md text-primary-light mb-16">Update Password</h6>
							<form action="{{ route('admin.profile.password') }}" method="POST" class="row g-3">
								@csrf
								<div class="col-12">
									<label class="form-label fw-semibold text-primary-light text-sm mb-8">Current Password <span class="text-danger-600">*</span></label>
									<div class="position-relative">
										<input type="password" name="current_password" class="form-control radius-8 @error('current_password') is-invalid @enderror" id="current-password" placeholder="Enter current password" required>
										<span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#current-password"></span>
										@error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
									</div>
								</div>
								<div class="col-12">
									<label class="form-label fw-semibold text-primary-light text-sm mb-8">New Password <span class="text-danger-600">*</span></label>
									<div class="position-relative">
										<input type="password" name="password" class="form-control radius-8 @error('password') is-invalid @enderror" id="new-password" placeholder="Enter new password" required>
										<span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#new-password"></span>
										@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
									</div>
								</div>
								<div class="col-12">
									<label class="form-label fw-semibold text-primary-light text-sm mb-8">Confirm Password <span class="text-danger-600">*</span></label>
									<div class="position-relative">
										<input type="password" name="password_confirmation" class="form-control radius-8" id="confirm-password" placeholder="Confirm new password" required>
										<span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#confirm-password"></span>
									</div>
								</div>
								<div class="col-12 d-flex align-items-center gap-3 mt-2">
									<button class="btn btn-primary radius-12 px-24">Update Password</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const toggleElements = document.querySelectorAll('.toggle-password');
		toggleElements.forEach(function (toggle) {
			toggle.addEventListener('click', function () {
				this.classList.toggle('ri-eye-off-line');
				const target = document.querySelector(this.getAttribute('data-toggle'));
				if (!target) return;
				if (target.type === 'password') {
					target.type = 'text';
				} else {
					target.type = 'password';
				}
			});
		});
	});
</script>
@endpush
