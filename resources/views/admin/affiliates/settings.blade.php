@extends('layouts.admin')

@section('title', 'Affiliate Settings')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Affiliate Settings</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.affiliates.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					Affiliates
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Settings</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0">
		<div class="card-body p-24">
			<form action="{{ route('admin.affiliates.settings.update') }}" method="POST" class="row g-4">
				@csrf
				
				<div class="col-12">
					<h6 class="fw-semibold mb-3">General Settings</h6>
				</div>

				<div class="col-md-6">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" name="affiliate_enabled" id="affiliate_enabled" 
							{{ old('affiliate_enabled', $settings['affiliate_enabled']) ? 'checked' : '' }}>
						<label class="form-check-label" for="affiliate_enabled">
							Enable Affiliate Program
						</label>
					</div>
					<div class="form-text">When disabled, the affiliate program will not be accessible to users.</div>
				</div>

				<div class="col-md-6">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" name="affiliate_auto_approve" id="affiliate_auto_approve" 
							{{ old('affiliate_auto_approve', $settings['affiliate_auto_approve']) ? 'checked' : '' }}>
						<label class="form-check-label" for="affiliate_auto_approve">
							Auto-approve Affiliates
						</label>
					</div>
					<div class="form-text">Automatically approve new affiliate applications without manual review.</div>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-3">Commission & Payout Settings</h6>
				</div>

				<div class="col-md-6">
					<label class="form-label text-secondary-light">Default Commission Rate (%)</label>
					<input type="number" name="affiliate_default_commission_rate" step="0.01" min="0" max="100" 
						class="form-control bg-neutral-50 radius-12 h-56-px @error('affiliate_default_commission_rate') is-invalid @enderror" 
						value="{{ old('affiliate_default_commission_rate', $settings['affiliate_default_commission_rate']) }}" required>
					@error('affiliate_default_commission_rate') 
						<div class="invalid-feedback d-block">{{ $message }}</div> 
					@enderror
					<div class="form-text">Default commission rate for new affiliates (can be customized per affiliate).</div>
				</div>

				<div class="col-md-6">
					<label class="form-label text-secondary-light">Minimum Payout Threshold</label>
					<input type="number" name="affiliate_min_payout_threshold" step="0.01" min="0" 
						class="form-control bg-neutral-50 radius-12 h-56-px @error('affiliate_min_payout_threshold') is-invalid @enderror" 
						value="{{ old('affiliate_min_payout_threshold', $settings['affiliate_min_payout_threshold']) }}" required>
					@error('affiliate_min_payout_threshold') 
						<div class="invalid-feedback d-block">{{ $message }}</div> 
					@enderror
					<div class="form-text">Minimum amount affiliates must have before requesting a payout.</div>
				</div>

				<div class="col-md-6">
					<label class="form-label text-secondary-light">Payout Processing Fee</label>
					<input type="number" name="affiliate_payout_processing_fee" step="0.01" min="0" 
						class="form-control bg-neutral-50 radius-12 h-56-px @error('affiliate_payout_processing_fee') is-invalid @enderror" 
						value="{{ old('affiliate_payout_processing_fee', $settings['affiliate_payout_processing_fee']) }}">
					@error('affiliate_payout_processing_fee') 
						<div class="invalid-feedback d-block">{{ $message }}</div> 
					@enderror
					<div class="form-text">Optional processing fee deducted from payouts (leave 0 for no fee).</div>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-3">Tracking Settings</h6>
				</div>

				<div class="col-md-6">
					<label class="form-label text-secondary-light">Cookie Period (Days)</label>
					<input type="number" name="affiliate_cookie_period" min="1" max="365" 
						class="form-control bg-neutral-50 radius-12 h-56-px @error('affiliate_cookie_period') is-invalid @enderror" 
						value="{{ old('affiliate_cookie_period', $settings['affiliate_cookie_period']) }}" required>
					@error('affiliate_cookie_period') 
						<div class="invalid-feedback d-block">{{ $message }}</div> 
					@enderror
					<div class="form-text">How long to track referrals after a user clicks an affiliate link (1-365 days).</div>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-3">Program Description</h6>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Program Description</label>
					<textarea name="affiliate_program_description" rows="5" 
						class="form-control bg-neutral-50 radius-12 @error('affiliate_program_description') is-invalid @enderror" 
						placeholder="Description shown on the public affiliate page">{{ old('affiliate_program_description', $settings['affiliate_program_description']) }}</textarea>
					@error('affiliate_program_description') 
						<div class="invalid-feedback d-block">{{ $message }}</div> 
					@enderror
					<div class="form-text">Custom description for the public affiliate program page. Leave empty to use default text.</div>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<div class="d-flex justify-content-end gap-3">
						<a href="{{ route('admin.affiliates.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
						<button type="submit" class="btn btn-primary radius-12 px-24">Save Settings</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

