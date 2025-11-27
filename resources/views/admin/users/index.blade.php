@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Users</h6>
			<p class="text-secondary-light mb-0">Manage administrators, staff, and customers.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Users</li>
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

	<div class="row">
		<div class="col-lg-3 mb-24">
			<div class="card border-0 h-100">
				<div class="card-body p-16">
					<div class="nav flex-column nav-pills gap-2" id="user-tabs" role="tablist" aria-orientation="vertical">
						<a href="{{ route('admin.users.index', ['tab' => 'admins']) }}" class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ ($activeTab ?? 'admins') === 'admins' ? 'active' : '' }}" id="user-tab-admins" type="button" role="tab">
							<span>Admins</span>
							<iconify-icon icon="solar:shield-user-outline" class="text-lg"></iconify-icon>
						</a>
						<a href="{{ route('admin.users.index', ['tab' => 'staff']) }}" class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ ($activeTab ?? '') === 'staff' ? 'active' : '' }}" id="user-tab-staff" type="button" role="tab">
							<span>Staff</span>
							<iconify-icon icon="solar:users-group-two-rounded-outline" class="text-lg"></iconify-icon>
						</a>
						<a href="{{ route('admin.users.index', ['tab' => 'customers']) }}" class="btn btn-outline-secondary d-flex justify-content-between align-items-center {{ ($activeTab ?? '') === 'customers' ? 'active' : '' }}" id="user-tab-customers" type="button" role="tab">
							<span>Customers</span>
							<iconify-icon icon="solar:user-id-outline" class="text-lg"></iconify-icon>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="tab-content" id="user-tabs-content">
				@if(($activeTab ?? 'admins') === 'admins')
					@include('admin.users.partials.admins')
				@elseif(($activeTab ?? '') === 'staff')
					@include('admin.users.partials.staff')
				@elseif(($activeTab ?? '') === 'customers')
					@include('admin.users.partials.customers')
				@else
					@include('admin.users.partials.admins')
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
