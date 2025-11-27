@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">General Settings</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
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
			<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="row g-4">
				@csrf
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Site Name</label>
					<input type="text" name="site_name" class="form-control bg-neutral-50 radius-12 h-56-px @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings['site_name']) }}" required>
					@error('site_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Tagline</label>
					<input type="text" name="site_tagline" class="form-control bg-neutral-50 radius-12 h-56-px @error('site_tagline') is-invalid @enderror" value="{{ old('site_tagline', $settings['site_tagline']) }}">
					@error('site_tagline') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-md-6">
					<label class="form-label text-secondary-light">Contact Email</label>
					<input type="email" name="contact_email" class="form-control bg-neutral-50 radius-12 h-56-px @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $settings['contact_email']) }}">
					@error('contact_email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-6">
					<label class="form-label text-secondary-light">Contact Phone</label>
					<input type="text" name="contact_phone" class="form-control bg-neutral-50 radius-12 h-56-px @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $settings['contact_phone']) }}">
					@error('contact_phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-md-6">
					<label class="form-label text-secondary-light">Street Address</label>
					<input type="text" name="contact_street" class="form-control bg-neutral-50 radius-12 h-56-px @error('contact_street') is-invalid @enderror" value="{{ old('contact_street', $settings['contact_street']) }}">
					@error('contact_street') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-3">
					<label class="form-label text-secondary-light">City</label>
					<input type="text" name="contact_city" class="form-control bg-neutral-50 radius-12 h-56-px @error('contact_city') is-invalid @enderror" value="{{ old('contact_city', $settings['contact_city']) }}">
					@error('contact_city') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-3">
					<label class="form-label text-secondary-light">State / Province</label>
					<input type="text" name="contact_state" class="form-control bg-neutral-50 radius-12 h-56-px @error('contact_state') is-invalid @enderror" value="{{ old('contact_state', $settings['contact_state']) }}">
					@error('contact_state') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-3">
					<label class="form-label text-secondary-light">Postal Code</label>
					<input type="text" name="contact_postal" class="form-control bg-neutral-50 radius-12 h-56-px @error('contact_postal') is-invalid @enderror" value="{{ old('contact_postal', $settings['contact_postal']) }}">
					@error('contact_postal') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-3">
					<label class="form-label text-secondary-light">Country</label>
					<input type="text" name="contact_country" class="form-control bg-neutral-50 radius-12 h-56-px @error('contact_country') is-invalid @enderror" value="{{ old('contact_country', $settings['contact_country']) }}">
					@error('contact_country') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-md-6">
					<label class="form-label text-secondary-light">Default Country Code</label>
					<input type="text" name="default_country_code" class="form-control bg-neutral-50 radius-12 h-56-px @error('default_country_code') is-invalid @enderror" value="{{ old('default_country_code', $settings['default_country_code']) }}" placeholder="91">
					@error('default_country_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Default country code for phone numbers (e.g., 91 for India, 1 for USA). This will be automatically added to phone numbers without country codes.</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Site Logo</label>
					<input type="file" name="site_logo" class="form-control bg-neutral-50 radius-12 h-56-px @error('site_logo') is-invalid @enderror" accept="image/*">
					@error('site_logo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Recommended size: 240×60 px (PNG or SVG with transparent background).</div>
					@if($settings['site_logo'])
						<div class="mt-12">
							<img src="{{ asset('storage/'.$settings['site_logo']) }}" alt="Site Logo" class="border radius-12" style="max-height:80px;">
						</div>
					@endif
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Dark Mode Logo</label>
					<input type="file" name="site_logo_dark" class="form-control bg-neutral-50 radius-12 h-56-px @error('site_logo_dark') is-invalid @enderror" accept="image/*">
					@error('site_logo_dark') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Recommended size: 240×60 px (PNG or SVG with transparent background).</div>
					@if($settings['site_logo_dark'])
						<div class="mt-12">
							<img src="{{ asset('storage/'.$settings['site_logo_dark']) }}" alt="Site Logo Dark" class="border radius-12" style="max-height:80px; background:#222; padding:12px;">
						</div>
					@endif
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Mobile Logo</label>
					<input type="file" name="site_logo_mobile" class="form-control bg-neutral-50 radius-12 h-56-px @error('site_logo_mobile') is-invalid @enderror" accept="image/*">
					@error('site_logo_mobile') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Logo specifically for mobile view. If not uploaded, desktop logo will be used.</div>
					@if($settings['site_logo_mobile'])
						<div class="mt-12">
							<img src="{{ asset('storage/'.$settings['site_logo_mobile']) }}" alt="Mobile Logo" class="border radius-12" style="max-height:80px;">
						</div>
					@endif
				</div>

				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Mobile Logo Width (px)</label>
					<input type="number" name="mobile_logo_width" class="form-control bg-neutral-50 radius-12 h-56-px @error('mobile_logo_width') is-invalid @enderror" value="{{ old('mobile_logo_width', $settings['mobile_logo_width']) }}" min="50" max="500">
					@error('mobile_logo_width') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Width in pixels (50-500)</div>
				</div>

				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Mobile Logo Height (px)</label>
					<input type="number" name="mobile_logo_height" class="form-control bg-neutral-50 radius-12 h-56-px @error('mobile_logo_height') is-invalid @enderror" value="{{ old('mobile_logo_height', $settings['mobile_logo_height']) }}" min="50" max="500">
					@error('mobile_logo_height') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Max height in pixels (50-500)</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Favicon</label>
					<input type="file" name="site_favicon" class="form-control bg-neutral-50 radius-12 h-56-px @error('site_favicon') is-invalid @enderror" accept="image/*">
					@error('site_favicon') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Recommended size: 64×64 px square (PNG with transparent background).</div>
					@if($settings['site_favicon'])
						<div class="mt-12">
							<img src="{{ asset('storage/'.$settings['site_favicon']) }}" alt="Site Favicon" class="border radius-12" style="max-height:48px;">
						</div>
					@endif
				</div>

				<div class="col-12 pt-8">
					<hr class="text-neutral-200 mb-24">
					<h6 class="fw-semibold mb-8">Analytics &amp; Tracking</h6>
					<p class="text-secondary-light mb-0">Paste your measurement IDs or verification snippets to enable tracking across the storefront and blog.</p>
				</div>
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Google Analytics Measurement ID</label>
					<input type="text" name="google_analytics_id" class="form-control bg-neutral-50 radius-12 h-56-px @error('google_analytics_id') is-invalid @enderror" value="{{ old('google_analytics_id', $settings['google_analytics_id']) }}" placeholder="G-XXXXXXXXXX">
					@error('google_analytics_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Google Tag Manager Container ID</label>
					<input type="text" name="google_tag_manager_id" class="form-control bg-neutral-50 radius-12 h-56-px @error('google_tag_manager_id') is-invalid @enderror" value="{{ old('google_tag_manager_id', $settings['google_tag_manager_id']) }}" placeholder="GTM-XXXXXXX">
					@error('google_tag_manager_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Google Search Console Verification</label>
					<textarea name="google_search_console_verification" rows="3" class="form-control bg-neutral-50 radius-12 @error('google_search_console_verification') is-invalid @enderror" placeholder="Paste the meta tag or verification string">{{ old('google_search_console_verification', $settings['google_search_console_verification']) }}</textarea>
					@error('google_search_console_verification') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">Save Settings</button>
					<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

