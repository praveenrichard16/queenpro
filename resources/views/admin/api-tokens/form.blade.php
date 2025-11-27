@extends('layouts.admin')

@section('title', $token ? 'Edit API Key' : 'Create API Key')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $token ? 'Edit API Key' : 'Create API Key' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.api-tokens.index') }}" class="hover-text-primary">API Keys</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $token ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	@if(session('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ session('error') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $token ? route('admin.api-tokens.update', $token) : route('admin.api-tokens.store') }}" class="row g-4">
				@csrf
				@if($token)
					@method('PUT')
				@endif

				<div class="col-12">
					<label class="form-label text-secondary-light">Token Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $token?->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required placeholder="e.g., Production API Key, Development Token">
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">A descriptive name for this API token to help you identify it later.</div>
				</div>

				@if(!$token)
					<div class="col-12">
						<label class="form-label text-secondary-light">User <span class="text-danger">*</span></label>
						<select name="user_id" class="form-control bg-neutral-50 radius-12 h-56-px @error('user_id') is-invalid @enderror" required>
							<option value="">Select a user</option>
							@foreach($users as $user)
								<option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
									{{ $user->name }} ({{ $user->email }}) 
									@if($user->is_admin || $user->is_super_admin)
										- Admin
									@elseif($user->is_staff)
										- Staff
									@else
										- Customer
									@endif
								</option>
							@endforeach
						</select>
						@error('user_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
						<div class="form-text mt-1">Select the user for whom this API token will be created.</div>
					</div>
				@endif

				<div class="col-12">
					<label class="form-label text-secondary-light mb-3">Abilities (Permissions)</label>
					@php
						$hasAllAbilities = $token && is_array($token->abilities) && in_array('*', $token->abilities);
						$abilities = $token && is_array($token->abilities) ? $token->abilities : [];
						// If token has all abilities, show all checkboxes as checked (user can uncheck to restrict)
						// If token has specific abilities, show only those checked
						// If new token, show none checked (will default to all permissions)
					@endphp
					<div class="d-flex flex-column gap-2">
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input border border-neutral-300" type="checkbox" value="read" id="ability_read" name="abilities[]" 
								@checked($hasAllAbilities || in_array('read', $abilities))>
							<label class="form-check-label ms-8" for="ability_read">
								<strong>Read</strong> - Allow reading/viewing data
							</label>
						</div>
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input border border-neutral-300" type="checkbox" value="write" id="ability_write" name="abilities[]"
								@checked($hasAllAbilities || in_array('write', $abilities))>
							<label class="form-check-label ms-8" for="ability_write">
								<strong>Write</strong> - Allow creating/updating data
							</label>
						</div>
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input border border-neutral-300" type="checkbox" value="delete" id="ability_delete" name="abilities[]"
								@checked($hasAllAbilities || in_array('delete', $abilities))>
							<label class="form-check-label ms-8" for="ability_delete">
								<strong>Delete</strong> - Allow deleting data
							</label>
						</div>
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input border border-neutral-300" type="checkbox" value="admin" id="ability_admin" name="abilities[]"
								@checked($hasAllAbilities || in_array('admin', $abilities))>
							<label class="form-check-label ms-8" for="ability_admin">
								<strong>Admin</strong> - Full administrative access
							</label>
						</div>
					</div>
					<div class="form-text mt-2">
						@if($hasAllAbilities)
							<strong class="text-info">This token currently has all permissions (*). Uncheck abilities above to restrict access to specific permissions only.</strong>
						@else
							Leave all unchecked to grant all permissions (*). Select specific abilities to restrict access.
						@endif
					</div>
					@error('abilities') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					@error('abilities.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				@if($token)
					<div class="col-12">
						<div class="alert alert-info">
							<h6 class="fw-semibold mb-2">Token Information</h6>
							<p class="mb-1"><strong>User:</strong> {{ $token->tokenable->name ?? 'N/A' }} ({{ $token->tokenable->email ?? 'N/A' }})</p>
							<p class="mb-1"><strong>Created:</strong> {{ $token->created_at->format('M d, Y h:i A') }}</p>
							@if($token->last_used_at)
								<p class="mb-0"><strong>Last Used:</strong> {{ $token->last_used_at->format('M d, Y h:i A') }} ({{ $token->last_used_at->diffForHumans() }})</p>
							@else
								<p class="mb-0"><strong>Last Used:</strong> Never</p>
							@endif
							<p class="mb-0 mt-2 text-danger"><strong>Note:</strong> The token value cannot be retrieved after creation. If you need a new token, delete this one and create a new one.</p>
						</div>
					</div>
				@endif

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $token ? 'Update API Key' : 'Create API Key' }}</button>
					<a href="{{ route('admin.api-tokens.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

