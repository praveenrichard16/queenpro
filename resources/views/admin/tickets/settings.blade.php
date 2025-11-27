@extends('layouts.admin')

@section('title', 'Support Notification Settings')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Support Notification Settings</h6>
			<p class="text-secondary-light mb-0">Control how ticket notifications are delivered and configure default SLA targets.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.support.tickets.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:lifebuoy-outline" class="icon text-lg"></iconify-icon>
					Support Tickets
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Notification Settings</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	@if($errors->any())
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please review the highlighted fields below.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0">
		<div class="card-body p-24">
			<form action="{{ route('admin.support.settings.update') }}" method="POST" class="row g-4">
				@csrf
				<div class="col-12">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" id="support_email_enabled" name="support_email_enabled" value="1" {{ old('support_email_enabled', $settings['support_email_enabled']) ? 'checked' : '' }}>
						<label class="form-check-label text-secondary-light" for="support_email_enabled">
							Enable email notifications for new tickets and updates
						</label>
					</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Team notification recipients</label>
					<textarea name="support_notify_addresses" rows="3" class="form-control bg-neutral-50 radius-12 @error('support_notify_addresses') is-invalid @enderror" placeholder="support@example.com, manager@example.com">{{ old('support_notify_addresses', $settings['support_notify_addresses']) }}</textarea>
					<div class="form-text">Separate email addresses with commas. These contacts receive alerts for new tickets and assignment changes.</div>
					@error('support_notify_addresses') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-md-6">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" id="support_email_customer_updates" name="support_email_customer_updates" value="1" {{ old('support_email_customer_updates', $settings['support_email_customer_updates']) ? 'checked' : '' }}>
						<label class="form-check-label text-secondary-light" for="support_email_customer_updates">
							Send email updates to customers when staff replies
						</label>
					</div>
				</div>

				<div class="col-md-6">
					<label class="form-label text-secondary-light">Default SLA policy</label>
					<select name="support_default_sla_id" class="form-select bg-neutral-50 radius-12 @error('support_default_sla_id') is-invalid @enderror">
						<option value="">No default SLA</option>
						@foreach($availableSlas as $sla)
							<option value="{{ $sla->id }}" @selected(old('support_default_sla_id', $settings['support_default_sla_id']) == $sla->id)>{{ $sla->name }}</option>
						@endforeach
					</select>
					<div class="form-text">Applied automatically when customers submit tickets.</div>
					@error('support_default_sla_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-3">
					<button class="btn btn-primary radius-12 px-24">Save Settings</button>
					<a href="{{ route('admin.support.tickets.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

