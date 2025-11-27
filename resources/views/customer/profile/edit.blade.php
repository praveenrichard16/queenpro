@extends('layouts.customer')

@section('title', 'Profile Settings')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Profile &amp; Preferences</h6>
			<p class="text-secondary-light mb-0">Update your personal information and keep your account secure.</p>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-xl-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Personal details</h6>
					<form method="POST" action="{{ route('customer.profile.update') }}" class="d-grid gap-3">
						@csrf
						@method('PUT')
						<div>
							<label class="form-label text-secondary-light">Full name</label>
							<input type="text" name="name" class="form-control bg-neutral-50 radius-12 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
							@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
						</div>
						<div>
							<label class="form-label text-secondary-light">Email</label>
							<input type="email" class="form-control bg-neutral-50 radius-12" value="{{ $user->email }}" disabled>
						</div>
						<div>
							<label class="form-label text-secondary-light">Phone</label>
							<input type="text" name="phone" class="form-control bg-neutral-50 radius-12 @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
							@error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
						</div>
						<div>
							<label class="form-label text-secondary-light">Timezone</label>
							<select name="timezone" class="form-select bg-neutral-50 radius-12 @error('timezone') is-invalid @enderror">
								<option value="">Use store default</option>
								@foreach($timezones as $timezone)
									<option value="{{ $timezone }}" @selected(old('timezone', $user->timezone) === $timezone)>{{ $timezone }}</option>
								@endforeach
							</select>
							@error('timezone') <div class="invalid-feedback">{{ $message }}</div> @enderror
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="marketing_opt_in" name="marketing_opt_in" value="1" @checked(old('marketing_opt_in', $user->marketing_opt_in))>
							<label class="form-check-label text-secondary-light" for="marketing_opt_in">
								Send me product updates and exclusive offers
							</label>
						</div>
						<button class="btn btn-primary radius-12 px-24 mt-8" type="submit">Save changes</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-xl-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Change password</h6>
					<form method="POST" action="{{ route('customer.profile.password') }}" class="d-grid gap-3">
						@csrf
						@method('PUT')
						<div>
							<label class="form-label text-secondary-light">Current password</label>
							<input type="password" name="current_password" class="form-control bg-neutral-50 radius-12 @error('current_password') is-invalid @enderror" required>
							@error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
						</div>
						<div>
							<label class="form-label text-secondary-light">New password</label>
							<input type="password" name="password" class="form-control bg-neutral-50 radius-12 @error('password') is-invalid @enderror" required>
							@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
						</div>
						<div>
							<label class="form-label text-secondary-light">Confirm new password</label>
							<input type="password" name="password_confirmation" class="form-control bg-neutral-50 radius-12" required>
						</div>
						<button class="btn btn-outline-secondary radius-12 px-24 mt-8" type="submit">Update password</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

