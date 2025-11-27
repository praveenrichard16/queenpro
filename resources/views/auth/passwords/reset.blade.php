@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<section class="auth-section py-80">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-5">
				<div class="card border-0 shadow-lg radius-24">
					<div class="card-body p-40">
						<div class="text-center mb-32">
							<h2 class="heading mb-12">Set a new password</h2>
							<p class="text-muted">Enter your new password below to complete the reset.</p>
						</div>
						<form method="POST" action="{{ route('password.update') }}" class="d-grid gap-3">
							@csrf
							<input type="hidden" name="token" value="{{ $token }}">
							<div>
								<label class="form-label text-secondary-light">Email address</label>
								<input type="email" name="email" class="form-control radius-16 bg-neutral-50 @error('email') is-invalid @enderror" value="{{ old('email', $email) }}" required autofocus>
								@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div>
								<label class="form-label text-secondary-light">New password</label>
								<input type="password" name="password" class="form-control radius-16 bg-neutral-50 @error('password') is-invalid @enderror" required>
								@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div>
								<label class="form-label text-secondary-light">Confirm password</label>
								<input type="password" name="password_confirmation" class="form-control radius-16 bg-neutral-50" required>
							</div>
							<button type="submit" class="shop-btn radius-16 py-3">Update password</button>
						</form>
						<div class="text-center mt-24 text-secondary-light">
							Remembered your password?
							<a href="{{ route('login') }}" class="text-primary fw-semibold">Sign in</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

