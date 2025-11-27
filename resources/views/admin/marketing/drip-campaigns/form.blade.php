@extends('layouts.admin')

@section('title', $campaign->exists ? 'Edit Drip Campaign' : 'Create Drip Campaign')

@php
	$timezones = [
		'UTC' => 'UTC',
		'Asia/Kolkata' => 'Asia/Kolkata (IST)',
		'Asia/Dubai' => 'Asia/Dubai',
		'Asia/Riyadh' => 'Asia/Riyadh',
		'Europe/London' => 'Europe/London',
	];
	$audienceOptions = [
		'existing_customers' => 'Customers with at least one order',
		'recent_enquiries' => 'Enquiries created in the last 30 days',
		'hot_leads' => 'Leads with score ≥ 70 or won stage',
		'abandoned_carts' => 'Customers with abandoned carts',
	];
	$waitOptions = [
		'' => 'No wait condition',
		'business_hours' => 'Next business window',
		'customer_response' => 'After customer response (12h)',
		'payment_received' => 'After payment received (24h)',
	];
	$selectedFilters = (array) old('audience_filters', $campaign->audience_filters ?? []);
@endphp

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $campaign->exists ? 'Edit Drip Campaign' : 'Create Drip Campaign' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.marketing.drip-campaigns.index') }}" class="hover-text-primary">Drip Campaigns</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $campaign->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<form method="POST" action="{{ $campaign->exists ? route('admin.marketing.drip-campaigns.update', $campaign) : route('admin.marketing.drip-campaigns.store') }}" id="dripCampaignForm">
		@csrf
		@if($campaign->exists)
			@method('PUT')
		@endif

		<div class="card border-0 mb-24">
			<div class="card-body p-24">
				<h6 class="fw-semibold mb-16">Campaign Details</h6>
				<div class="row g-4">
					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Campaign Name <span class="text-danger">*</span></label>
						<input type="text" name="name" value="{{ old('name', $campaign->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
						@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Trigger Type <span class="text-danger">*</span></label>
						<select name="trigger_type" class="form-select bg-neutral-50 radius-12 h-56-px @error('trigger_type') is-invalid @enderror" required>
							<option value="new_enquiry" @selected(old('trigger_type', $campaign->trigger_type) === 'new_enquiry')>New Enquiry</option>
							<option value="new_lead" @selected(old('trigger_type', $campaign->trigger_type) === 'new_lead')>New Lead</option>
							<option value="manual" @selected(old('trigger_type', $campaign->trigger_type) === 'manual')>Manual Trigger</option>
						</select>
						@error('trigger_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Channel <span class="text-danger">*</span></label>
						<select name="channel" class="form-select bg-neutral-50 radius-12 h-56-px @error('channel') is-invalid @enderror" required>
							<option value="email" @selected(old('channel', $campaign->channel) === 'email')>Email</option>
							<option value="whatsapp" @selected(old('channel', $campaign->channel) === 'whatsapp')>WhatsApp</option>
							<option value="both" @selected(old('channel', $campaign->channel) === 'both')>Both</option>
						</select>
						@error('channel') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Status</label>
						<div class="form-check form-switch mt-2">
							<input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="is_active" {{ old('is_active', $campaign->is_active ?? true) ? 'checked' : '' }}>
							<label class="form-check-label" for="is_active">Active</label>
						</div>
					</div>

					<div class="col-12">
						<label class="form-label text-secondary-light">Description</label>
						<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror">{{ old('description', $campaign->description) }}</textarea>
						@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-4">
						<label class="form-label text-secondary-light">Timezone</label>
						<select name="timezone" class="form-select bg-neutral-50 radius-12 @error('timezone') is-invalid @enderror">
							@foreach($timezones as $tz => $label)
								<option value="{{ $tz }}" @selected(old('timezone', $campaign->timezone ?? config('app.timezone')) === $tz)>{{ $label }}</option>
							@endforeach
						</select>
						@error('timezone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-4">
						<label class="form-label text-secondary-light">Send Window Start</label>
						<input type="time" name="send_window_start" value="{{ old('send_window_start', $campaign->send_window_start) }}" class="form-control bg-neutral-50 radius-12 @error('send_window_start') is-invalid @enderror">
						@error('send_window_start') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-4">
						<label class="form-label text-secondary-light">Send Window End</label>
						<input type="time" name="send_window_end" value="{{ old('send_window_end', $campaign->send_window_end) }}" class="form-control bg-neutral-50 radius-12 @error('send_window_end') is-invalid @enderror">
						@error('send_window_end') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-4">
						<label class="form-label text-secondary-light">Max Retries</label>
						<input type="number" name="max_retries" min="0" max="10" value="{{ old('max_retries', $campaign->max_retries ?? 3) }}" class="form-control bg-neutral-50 radius-12 @error('max_retries') is-invalid @enderror">
						@error('max_retries') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>

					<div class="col-lg-8">
						<label class="form-label text-secondary-light">Audience Filters</label>
						<div class="row g-2">
							@foreach($audienceOptions as $value => $label)
								<div class="col-md-6">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="audience_filters[]" value="{{ $value }}" id="aud-{{ $value }}" @checked(in_array($value, $selectedFilters, true))>
										<label class="form-check-label" for="aud-{{ $value }}">{{ $label }}</label>
									</div>
								</div>
							@endforeach
						</div>
						<small class="text-secondary-light">Leave all unchecked to target all recipients.</small>
						@error('audience_filters') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>
				</div>
			</div>
		</div>

		<div class="card border-0 mb-24">
			<div class="card-body p-24">
				<div class="d-flex justify-content-between align-items-center mb-16">
					<h6 class="fw-semibold mb-0">Campaign Steps</h6>
					<button type="button" class="btn btn-primary btn-sm" id="addStepBtn">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add Step
					</button>
				</div>

				<div id="stepsContainer">
					@if($campaign->exists && $campaign->steps->count() > 0)
						@foreach($campaign->steps as $index => $step)
							@include('admin.marketing.drip-campaigns.partials.step-row', ['step' => $step, 'index' => $index, 'templates' => $templates, 'waitOptions' => $waitOptions])
						@endforeach
					@else
						@include('admin.marketing.drip-campaigns.partials.step-row', ['step' => null, 'index' => 0, 'templates' => $templates, 'waitOptions' => $waitOptions])
					@endif
				</div>
			</div>
		</div>

		<div class="d-flex gap-2">
			<button type="submit" class="btn btn-primary radius-12 px-24">{{ $campaign->exists ? 'Update Campaign' : 'Create Campaign' }}</button>
			<a href="{{ route('admin.marketing.drip-campaigns.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
		</div>
	</form>
</div>
@endsection

@push('scripts')
<script>
	let stepIndex = {{ $campaign->exists && $campaign->steps->count() > 0 ? $campaign->steps->count() : 1 }};
	const templates = @json($templates);
	const waitOptions = @json($waitOptions);

	document.getElementById('addStepBtn').addEventListener('click', function() {
		const container = document.getElementById('stepsContainer');
		const stepRow = document.createElement('div');
		stepRow.className = 'step-row border radius-12 p-3 mb-3';
		stepRow.innerHTML = getStepRowHtml(stepIndex);
		container.appendChild(stepRow);
		stepIndex++;
	});

	document.addEventListener('click', function(e) {
		if (e.target.classList.contains('remove-step')) {
			e.target.closest('.step-row').remove();
		}
	});

	function getStepRowHtml(index) {
		let templateOptions = '';
		templates.forEach(template => {
			templateOptions += `<option value="${template.id}">${template.name} (${template.type})</option>`;
		});

		let waitConditionOptions = '';
		Object.entries(waitOptions).forEach(([value, label]) => {
			waitConditionOptions += `<option value="${value}">${label}</option>`;
		});

		return `
			<div class="row g-3 align-items-end">
				<div class="col-md-1">
					<label class="form-label text-secondary-light">Step #</label>
					<input type="number" name="steps[${index}][step_number]" value="${index + 1}" class="form-control bg-neutral-50 radius-12" required min="1">
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">Delay (Hours)</label>
					<input type="number" name="steps[${index}][delay_hours]" value="0" class="form-control bg-neutral-50 radius-12" required min="0">
				</div>
				<div class="col-md-3">
					<label class="form-label text-secondary-light">Template</label>
					<select name="steps[${index}][template_id]" class="form-select bg-neutral-50 radius-12" required>
						<option value="">Select Template</option>
						${templateOptions}
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">Channel</label>
					<select name="steps[${index}][channel]" class="form-select bg-neutral-50 radius-12" required>
						<option value="email">Email</option>
						<option value="whatsapp">WhatsApp</option>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">Status</label>
					<div class="form-check form-switch mt-2">
						<input class="form-check-input" type="checkbox" name="steps[${index}][is_active]" value="1" checked>
						<label class="form-check-label">Active</label>
					</div>
				</div>
				<div class="col-md-3">
					<label class="form-label text-secondary-light">Wait Condition</label>
					<select name="steps[${index}][wait_until_event]" class="form-select bg-neutral-50 radius-12">
						${waitConditionOptions}
					</select>
				</div>
				<div class="col-12">
					<label class="form-label text-secondary-light">Conditions (JSON or key:value per line)</label>
					<textarea name="steps[${index}][conditions]" rows="2" class="form-control bg-neutral-50 radius-12" placeholder='{\"lead_stage\":\"proposal\"}'></textarea>
				</div>
				<div class="col-md-2">
					<button type="button" class="btn btn-danger btn-sm remove-step">
						<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
						Remove
					</button>
				</div>
			</div>
		`;
	}
</script>
@endpush

