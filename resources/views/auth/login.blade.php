@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<section class="auth bg-base d-flex flex-wrap min-vh-100">
	<div class="auth-left d-lg-block d-none">
		<div class="d-flex align-items-center flex-column h-100 justify-content-center p-24">
			<img src="{{ asset('wowdash/assets/images/auth/auth-img.png') }}" alt="Auth Illustration">
		</div>
	</div>
	<div class="auth-right py-32 px-24 d-flex flex-column justify-content-center flex-grow-1">
		<div class="max-w-464-px mx-auto w-100">
			<div class="mb-32 text-center text-lg-start">
				<a href="{{ url('/') }}" class="d-inline-flex align-items-center mb-32">
					<img src="{{ asset('wowdash/assets/images/logo.png') }}" alt="Logo" class="max-w-230-px">
				</a>
				<h4 class="mb-12">Sign In to your Account</h4>
				<p class="mb-0 text-secondary-light text-lg">Welcome back! Please enter your credentials</p>
			</div>

			<form method="POST" action="{{ route('login.perform') }}" class="mt-32">
				@csrf
				@if(isset($redirect))
					<input type="hidden" name="redirect" value="{{ $redirect }}">
				@endif
				<div class="icon-field mb-16">
					<span class="icon top-50 translate-middle-y">
						<iconify-icon icon="mage:email"></iconify-icon>
					</span>
					<input type="email" name="email" value="{{ old('email') }}" class="form-control h-56-px bg-neutral-50 radius-12 @error('email') is-invalid @enderror" placeholder="Email address" required autofocus>
					@error('email')
						<div class="invalid-feedback d-block">{{ $message }}</div>
					@enderror
				</div>

				<div class="position-relative mb-20">
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y">
							<iconify-icon icon="solar:lock-password-outline"></iconify-icon>
						</span>
						<input type="password" name="password" id="admin-password" class="form-control h-56-px bg-neutral-50 radius-12 @error('password') is-invalid @enderror" placeholder="Password" required>
					</div>
					<span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#admin-password"></span>
					@error('password')
						<div class="invalid-feedback d-block">{{ $message }}</div>
					@enderror
				</div>

				<div class="d-flex justify-content-between gap-2 flex-wrap">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
						<label class="form-check-label ms-8" for="remember">Remember me</label>
					</div>
					@if (Route::has('password.request'))
						<a href="{{ route('password.request') }}" class="text-primary-600 fw-medium">Forgot Password?</a>
					@endif
				</div>

				<button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Sign In</button>
			</form>

			<div class="mt-32 text-center text-sm">
				<p class="mb-0">Need an account? <a href="{{ route('register') }}" class="text-primary-600 fw-semibold">Create one now</a></p>
			</div>
		</div>
	</div>
</section>
@endsection

@push('scripts')
<script>
	function initializePasswordToggle(selector) {
		$(selector).on('click', function () {
			$(this).toggleClass('ri-eye-off-line');
			const target = $($(this).attr('data-toggle'));
			target.attr('type', target.attr('type') === 'password' ? 'text' : 'password');
		});
	}

	initializePasswordToggle('.toggle-password');
</script>
@endpush
