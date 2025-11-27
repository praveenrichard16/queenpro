@extends('layouts.customer')

@section('title', 'Become an Affiliate')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Become an Affiliate</h6>
			<p class="text-secondary-light mb-0">Earn commissions by referring customers</p>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card border-0 shadow-sm">
				<div class="card-body p-24">
					<div class="text-center mb-24">
						<iconify-icon icon="solar:hand-stars-outline" class="text-5xl text-primary mb-3"></iconify-icon>
						<h4 class="fw-semibold mb-2">Join Our Affiliate Program</h4>
						<p class="text-secondary-light">Earn money by sharing our products with your audience</p>
					</div>

					<div class="mb-24">
						<h6 class="fw-semibold mb-3">How it works:</h6>
						<ul class="list-unstyled">
							<li class="d-flex align-items-start gap-3 mb-3">
								<iconify-icon icon="solar:check-circle-bold" class="text-success text-lg"></iconify-icon>
								<div>
									<strong>Get your unique link</strong>
									<p class="text-secondary-light small mb-0">Receive a personalized referral link after approval</p>
								</div>
							</li>
							<li class="d-flex align-items-start gap-3 mb-3">
								<iconify-icon icon="solar:check-circle-bold" class="text-success text-lg"></iconify-icon>
								<div>
									<strong>Share with your audience</strong>
									<p class="text-secondary-light small mb-0">Share your link on social media, blog, or website</p>
								</div>
							</li>
							<li class="d-flex align-items-start gap-3 mb-3">
								<iconify-icon icon="solar:check-circle-bold" class="text-success text-lg"></iconify-icon>
								<div>
									<strong>Earn commissions</strong>
									<p class="text-secondary-light small mb-0">Get paid when customers make purchases through your link</p>
								</div>
							</li>
							<li class="d-flex align-items-start gap-3">
								<iconify-icon icon="solar:check-circle-bold" class="text-success text-lg"></iconify-icon>
								<div>
									<strong>Receive payouts</strong>
									<p class="text-secondary-light small mb-0">Get your earnings transferred to your account</p>
								</div>
							</li>
						</ul>
					</div>

					<form method="POST" action="{{ route('customer.affiliates.apply') }}">
						@csrf
						<div class="d-grid">
							<button type="submit" class="btn btn-primary radius-12 px-24 py-3">
								Apply to Become an Affiliate
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

