@extends('layouts.admin')

@section('title', $campaign->id ? 'Edit Campaign' : 'Create Campaign')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $campaign->id ? 'Edit Campaign' : 'Create Campaign' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.marketing.campaigns.index') }}" class="hover-text-primary">Campaigns</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $campaign->id ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $campaign->exists ? route('admin.marketing.campaigns.update', $campaign) : route('admin.marketing.campaigns.store') }}" class="row g-4">
				@csrf
				@if($campaign->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Campaign Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $campaign->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Template <span class="text-danger">*</span></label>
					<select name="template_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('template_id') is-invalid @enderror" required>
						<option value="">Select template</option>
						@foreach($templates as $template)
							<option value="{{ $template->id }}" @selected(old('template_id', $campaign->template_id) == $template->id)>
								{{ $template->name }} ({{ ucfirst($template->type) }})
							</option>
						@endforeach
					</select>
					@error('template_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Status</label>
					<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px">
						<option value="draft" @selected(old('status', $campaign->status ?? 'draft') === 'draft')>Draft</option>
						<option value="scheduled" @selected(old('status', $campaign->status) === 'scheduled')>Scheduled</option>
					</select>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Scheduled At</label>
					<input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i')) }}" class="form-control bg-neutral-50 radius-12 h-56-px">
				</div>

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Recipient Filters</h6>
					<div class="row g-3">
						<div class="col-lg-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="recipient_filters[has_orders]" value="1" id="has_orders" @checked(old('recipient_filters.has_orders', $campaign->recipient_filters['has_orders'] ?? false))>
								<label class="form-check-label" for="has_orders">Has Orders</label>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="recipient_filters[cart_abandoners]" value="1" id="cart_abandoners" @checked(old('recipient_filters.cart_abandoners', $campaign->recipient_filters['cart_abandoners'] ?? false))>
								<label class="form-check-label" for="cart_abandoners">Cart Abandoners</label>
							</div>
						</div>
						<div class="col-lg-4">
							<label class="form-label text-secondary-light">Min Orders</label>
							<input type="number" name="recipient_filters[min_orders]" value="{{ old('recipient_filters.min_orders', $campaign->recipient_filters['min_orders'] ?? '') }}" class="form-control bg-neutral-50 radius-12 h-56-px" min="0">
						</div>
					</div>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $campaign->id ? 'Update Campaign' : 'Create Campaign' }}</button>
					<a href="{{ route('admin.marketing.campaigns.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

