@php
	$address = $address ?? null;
@endphp

<div class="row g-4">
	<div class="col-md-6">
		<label class="form-label text-secondary-light">Label</label>
		<input type="text" name="label" class="form-control bg-neutral-50 radius-12 @error('label') is-invalid @enderror" value="{{ old('label', $address->label ?? 'Home') }}" required>
		@error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-6">
		<label class="form-label text-secondary-light">Type</label>
		<select name="type" class="form-select bg-neutral-50 radius-12 @error('type') is-invalid @enderror">
			<option value="shipping" @selected(old('type', $address->type ?? 'shipping') === 'shipping')>Shipping</option>
			<option value="billing" @selected(old('type', $address->type ?? 'shipping') === 'billing')>Billing</option>
		</select>
		@error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-6">
		<label class="form-label text-secondary-light">Contact name</label>
		<input type="text" name="contact_name" class="form-control bg-neutral-50 radius-12 @error('contact_name') is-invalid @enderror" value="{{ old('contact_name', $address->contact_name ?? auth()->user()->name) }}">
		@error('contact_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-6">
		<label class="form-label text-secondary-light">Contact phone</label>
		<input type="text" name="contact_phone" class="form-control bg-neutral-50 radius-12 @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $address->contact_phone ?? auth()->user()->phone) }}">
		@error('contact_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-12">
		<label class="form-label text-secondary-light">Street address</label>
		<input type="text" name="street" class="form-control bg-neutral-50 radius-12 @error('street') is-invalid @enderror" value="{{ old('street', $address->street ?? '') }}" required>
		@error('street') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-4">
		<label class="form-label text-secondary-light">City</label>
		<input type="text" name="city" class="form-control bg-neutral-50 radius-12 @error('city') is-invalid @enderror" value="{{ old('city', $address->city ?? '') }}" required>
		@error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-4">
		<label class="form-label text-secondary-light">State</label>
		<input type="text" name="state" class="form-control bg-neutral-50 radius-12 @error('state') is-invalid @enderror" value="{{ old('state', $address->state ?? '') }}">
		@error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-4">
		<label class="form-label text-secondary-light">Postal code</label>
		<input type="text" name="postal_code" class="form-control bg-neutral-50 radius-12 @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $address->postal_code ?? '') }}">
		@error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-6">
		<label class="form-label text-secondary-light">Country</label>
		<input type="text" name="country" class="form-control bg-neutral-50 radius-12 @error('country') is-invalid @enderror" value="{{ old('country', $address->country ?? 'Saudi Arabia') }}" required>
		@error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
	</div>
	<div class="col-md-6 d-flex align-items-center">
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" name="is_default" value="1" id="address-default" @checked(old('is_default', $address->is_default ?? false))>
			<label class="form-check-label text-secondary-light" for="address-default">
				Set as default {{ old('type', $address->type ?? 'shipping') }} address
			</label>
		</div>
	</div>
</div>

