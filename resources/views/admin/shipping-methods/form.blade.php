@extends('layouts.admin')

@section('title', $shippingMethod->exists ? 'Edit Shipping Method' : 'Add Shipping Method')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $shippingMethod->exists ? 'Edit Shipping Method' : 'Add Shipping Method' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.shipping-methods.index') }}" class="hover-text-primary">Shipping Methods</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $shippingMethod->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $shippingMethod->exists ? route('admin.shipping-methods.update', $shippingMethod) : route('admin.shipping-methods.store') }}" class="row g-4">
				@csrf
				@if($shippingMethod->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $shippingMethod->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Code <span class="text-danger">*</span></label>
					<input type="text" name="code" value="{{ old('code', $shippingMethod->code) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('code') is-invalid @enderror" required placeholder="e.g., cash_on_delivery">
					@error('code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Unique identifier (lowercase, underscores only).</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Type <span class="text-danger">*</span></label>
					<select name="type" class="form-select bg-neutral-50 radius-12 h-56-px @error('type') is-invalid @enderror" required id="shipping_type">
						<option value="flat_rate" @selected(old('type', $shippingMethod->type) === 'flat_rate')>Flat Rate</option>
						<option value="free_shipping" @selected(old('type', $shippingMethod->type) === 'free_shipping')>Free Shipping</option>
						<option value="weight_based" @selected(old('type', $shippingMethod->type) === 'weight_based')>Weight Based</option>
						<option value="location_based" @selected(old('type', $shippingMethod->type) === 'location_based')>Location Based</option>
					</select>
					@error('type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Base Cost <span class="text-danger">*</span></label>
					<input type="number" name="cost" value="{{ old('cost', $shippingMethod->cost) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('cost') is-invalid @enderror" required>
					@error('cost') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Base shipping cost in {{ \App\Services\CurrencyService::code() }}.</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Free Shipping Threshold</label>
					<input type="number" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $shippingMethod->free_shipping_threshold) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('free_shipping_threshold') is-invalid @enderror" placeholder="Leave empty for no threshold">
					@error('free_shipping_threshold') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Minimum order amount for free shipping (optional).</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Sort Order</label>
					<input type="number" name="sort_order" value="{{ old('sort_order', $shippingMethod->sort_order ?? 0) }}" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('sort_order') is-invalid @enderror">
					@error('sort_order') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Display order (lower numbers appear first).</div>
				</div>

				<div class="col-12">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="is_active" name="is_active" @checked(old('is_active', $shippingMethod->is_active ?? true))>
						<label class="form-check-label ms-8" for="is_active">
							Active
						</label>
					</div>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $shippingMethod->exists ? 'Update Shipping Method' : 'Create Shipping Method' }}</button>
					<a href="{{ route('admin.shipping-methods.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

