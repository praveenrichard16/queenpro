@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<section class="auth-section py-80">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="card border-0 shadow-lg radius-24">
					<div class="card-body p-40">
						<div class="text-center mb-32">
							<h2 class="heading mb-12">Create your account</h2>
							<p class="text-muted">Join {{ $appSettings['site_name'] ?? config('app.name') }} for fast checkout and order tracking.</p>
						</div>
						<form method="POST" action="{{ route('register.perform') }}" class="d-grid gap-3">
							@csrf
							<div>
								<label class="form-label text-secondary-light">Full name</label>
								<input type="text" name="name" class="form-control radius-16 bg-neutral-50 @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
								@error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div>
								<label class="form-label text-secondary-light">Email address</label>
								<input type="email" name="email" class="form-control radius-16 bg-neutral-50 @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
								@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Password</label>
									<input type="password" name="password" class="form-control radius-16 bg-neutral-50 @error('password') is-invalid @enderror" required>
									@error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Confirm password</label>
									<input type="password" name="password_confirmation" class="form-control radius-16 bg-neutral-50" required>
								</div>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="1" id="marketing_opt_in" name="marketing_opt_in" {{ old('marketing_opt_in', true) ? 'checked' : '' }}>
								<label class="form-check-label text-secondary-light" for="marketing_opt_in">
									Send me product updates and exclusive offers.
								</label>
							</div>
							<button type="submit" class="shop-btn radius-16 py-3">Create account</button>
						</form>
						<div class="text-center mt-24 text-secondary-light">
							Already have an account?
							<a href="{{ route('login') }}" class="text-primary fw-semibold">Sign in</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

