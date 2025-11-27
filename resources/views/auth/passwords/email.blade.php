@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<section class="auth-section py-80">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-5">
				<div class="card border-0 shadow-lg radius-24">
					<div class="card-body p-40">
						<div class="text-center mb-32">
							<h2 class="heading mb-12">Forgot your password?</h2>
							<p class="text-muted">Enter your email and we’ll send you a link to reset your password.</p>
						</div>
						@if (session('status'))
							<div class="alert alert-success">{{ session('status') }}</div>
						@endif
						<form method="POST" action="{{ route('password.email') }}" class="d-grid gap-3">
							@csrf
							<div>
								<label class="form-label text-secondary-light">Email address</label>
								<input type="email" name="email" class="form-control radius-16 bg-neutral-50 @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
								@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
							</div>
							<button type="submit" class="shop-btn radius-16 py-3">Send reset link</button>
						</form>
						<div class="text-center mt-24 text-secondary-light">
							Remembered your password?
							<a href="{{ route('login') }}" class="text-primary fw-semibold">Back to sign in</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

