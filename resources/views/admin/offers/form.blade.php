@extends('layouts.admin')

@section('title', $offer->exists ? 'Edit Offer' : 'Create Offer')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $offer->exists ? 'Edit Offer' : 'Create Offer' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.offers.index') }}" class="hover-text-primary">Offers</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $offer->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $offer->exists ? route('admin.offers.update', $offer) : route('admin.offers.store') }}" class="row g-4" id="offerForm">
				@csrf
				@if($offer->exists)
					@method('PUT')
				@endif

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Basic Information</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Coupon Code <span class="text-danger">*</span></label>
					<input type="text" name="code" value="{{ old('code', $offer->code) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('code') is-invalid @enderror" required>
					@error('code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Unique code for customers to use at checkout.</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Offer Type <span class="text-danger">*</span></label>
					<select name="offer_type" id="offer_type" class="form-select bg-neutral-50 radius-12 h-56-px @error('offer_type') is-invalid @enderror" required>
						<option value="common" @selected(old('offer_type', $offer->offer_type ?? 'common') === 'common')>Common/Public Coupon</option>
						<option value="product" @selected(old('offer_type', $offer->offer_type) === 'product')>Product-wise</option>
						<option value="category" @selected(old('offer_type', $offer->offer_type) === 'category')>Category-wise</option>
						<option value="brand" @selected(old('offer_type', $offer->offer_type) === 'brand')>Brand-wise</option>
						<option value="user" @selected(old('offer_type', $offer->offer_type) === 'user')>User-specific</option>
						<option value="billing_amount" @selected(old('offer_type', $offer->offer_type) === 'billing_amount')>Billing Amount</option>
					</select>
					@error('offer_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Discount Type <span class="text-danger">*</span></label>
					<select name="type" id="discount_type" class="form-select bg-neutral-50 radius-12 h-56-px @error('type') is-invalid @enderror" required>
						<option value="percentage" @selected(old('type', $offer->type) === 'percentage')>Percentage</option>
						<option value="fixed" @selected(old('type', $offer->type) === 'fixed')>Fixed Amount</option>
					</select>
					@error('type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Discount Value <span class="text-danger">*</span></label>
					<div class="input-group">
						<input type="number" name="value" id="discount_value" value="{{ old('value', $offer->value) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('value') is-invalid @enderror" required>
						<span class="input-group-text bg-neutral-50" id="discount_unit">%</span>
					</div>
					@error('value') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1" id="discount_help">Enter percentage (0-100)</div>
					@php
						$currencySymbol = \App\Services\CurrencyService::symbol();
					@endphp
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Minimum Amount</label>
					<input type="number" name="min_amount" value="{{ old('min_amount', $offer->min_amount) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('min_amount') is-invalid @enderror">
					@error('min_amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Minimum cart total required to use this offer.</div>
				</div>

				<div class="col-lg-6" id="max_discount_field">
					<label class="form-label text-secondary-light">Maximum Discount</label>
					<input type="number" name="max_discount" value="{{ old('max_discount', $offer->max_discount) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('max_discount') is-invalid @enderror">
					@error('max_discount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Maximum discount amount (for percentage discounts).</div>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Offer Targeting</h6>
				</div>

				<!-- Product Selection -->
				<div class="col-12" id="product_selection" style="display: none;">
					<label class="form-label text-secondary-light">Select Products <span class="text-danger">*</span></label>
					<select name="product_ids[]" id="product_ids" class="form-select bg-neutral-50 radius-12 @error('product_ids') is-invalid @enderror" multiple size="8">
						@foreach($products as $product)
							<option value="{{ $product->id }}" @selected(in_array($product->id, old('product_ids', $offer->products->pluck('id')->toArray())))>
								{{ $product->name }} ({{ \App\Services\CurrencyService::format($product->effective_price) }})
							</option>
						@endforeach
					</select>
					@error('product_ids') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Hold Ctrl/Cmd to select multiple products.</div>
				</div>

				<!-- Category Selection -->
				<div class="col-12" id="category_selection" style="display: none;">
					<label class="form-label text-secondary-light">Select Categories <span class="text-danger">*</span></label>
					<select name="category_ids[]" id="category_ids" class="form-select bg-neutral-50 radius-12 @error('category_ids') is-invalid @enderror" multiple size="8">
						@foreach($categories as $category)
							<option value="{{ $category->id }}" @selected(in_array($category->id, old('category_ids', $offer->categories->pluck('id')->toArray())))>
								{{ $category->name }}
							</option>
						@endforeach
					</select>
					@error('category_ids') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Hold Ctrl/Cmd to select multiple categories.</div>
				</div>

				<!-- Brand Selection -->
				<div class="col-12" id="brand_selection" style="display: none;">
					<label class="form-label text-secondary-light">Select Brands <span class="text-danger">*</span></label>
					<select name="brand_ids[]" id="brand_ids" class="form-select bg-neutral-50 radius-12 @error('brand_ids') is-invalid @enderror" multiple size="8">
						@foreach($brands as $brand)
							<option value="{{ $brand->id }}" @selected(in_array($brand->id, old('brand_ids', $offer->brands->pluck('id')->toArray())))>
								{{ $brand->name }}
							</option>
						@endforeach
					</select>
					@error('brand_ids') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Hold Ctrl/Cmd to select multiple brands.</div>
				</div>

				<!-- User Selection -->
				<div class="col-12" id="user_selection" style="display: none;">
					<label class="form-label text-secondary-light">Select Users <span class="text-danger">*</span></label>
					<select name="user_ids[]" id="user_ids" class="form-select bg-neutral-50 radius-12 @error('user_ids') is-invalid @enderror" multiple size="8">
						@foreach($users as $user)
							<option value="{{ $user->id }}" @selected(in_array($user->id, old('user_ids', $offer->users->pluck('id')->toArray())))>
								{{ $user->name }} ({{ $user->email }})
							</option>
						@endforeach
					</select>
					@error('user_ids') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Hold Ctrl/Cmd to select multiple users.</div>
				</div>

				<!-- Billing Amount Field -->
				<div class="col-lg-6" id="billing_amount_field" style="display: none;">
					<label class="form-label text-secondary-light">Minimum Billing Amount <span class="text-danger">*</span></label>
					<input type="number" name="min_amount" value="{{ old('min_amount', $offer->min_amount) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('min_amount') is-invalid @enderror">
					@error('min_amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Minimum cart total required.</div>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">User Segmentation</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">User Segment</label>
					<select name="user_segment" id="user_segment" class="form-select bg-neutral-50 radius-12 h-56-px @error('user_segment') is-invalid @enderror">
						<option value="">All Users</option>
						<option value="first_time_buyers" @selected(old('user_segment', $offer->user_segment) === 'first_time_buyers')>First Time Buyers</option>
						<option value="repeat_customers" @selected(old('user_segment', $offer->user_segment) === 'repeat_customers')>Repeat Customers</option>
						<option value="minimum_purchase" @selected(old('user_segment', $offer->user_segment) === 'minimum_purchase')>Minimum Purchase Amount</option>
					</select>
					@error('user_segment') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6" id="minimum_purchase_amount_field" style="display: none;">
					<label class="form-label text-secondary-light">Minimum Purchase Amount <span class="text-danger">*</span></label>
					<input type="number" name="minimum_purchase_amount" value="{{ old('minimum_purchase_amount', $offer->minimum_purchase_amount) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px @error('minimum_purchase_amount') is-invalid @enderror">
					@error('minimum_purchase_amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Usage & Validity</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Usage Limit</label>
					<input type="number" name="usage_limit" value="{{ old('usage_limit', $offer->usage_limit) }}" min="1" class="form-control bg-neutral-50 radius-12 h-56-px @error('usage_limit') is-invalid @enderror">
					@error('usage_limit') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Total number of times this offer can be used. Leave empty for unlimited.</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Per User Limit</label>
					<input type="number" name="per_user_limit" value="{{ old('per_user_limit', $offer->per_user_limit) }}" min="1" class="form-control bg-neutral-50 radius-12 h-56-px @error('per_user_limit') is-invalid @enderror">
					@error('per_user_limit') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">How many times a single user can use this offer.</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Valid From</label>
					<input type="datetime-local" name="valid_from" value="{{ old('valid_from', $offer->valid_from ? $offer->valid_from->format('Y-m-d\TH:i') : '') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('valid_from') is-invalid @enderror">
					@error('valid_from') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Valid Until</label>
					<input type="datetime-local" name="valid_until" value="{{ old('valid_until', $offer->valid_until ? $offer->valid_until->format('Y-m-d\TH:i') : '') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('valid_until') is-invalid @enderror">
					@error('valid_until') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Additional Settings</h6>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description</label>
					<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror" placeholder="Offer description">{{ old('description', $offer->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="is_public" name="is_public" @checked(old('is_public', $offer->is_public ?? true))>
						<label class="form-check-label ms-8" for="is_public">
							Public/Common Coupon
						</label>
					</div>
					<div class="form-text mt-1">Make this offer available to all users.</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Status <span class="text-danger">*</span></label>
					<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px @error('status') is-invalid @enderror" required>
						<option value="active" @selected(old('status', $offer->status ?? 'active') === 'active')>Active</option>
						<option value="inactive" @selected(old('status', $offer->status) === 'inactive')>Inactive</option>
					</select>
					@error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $offer->exists ? 'Update Offer' : 'Create Offer' }}</button>
					<a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const offerType = document.getElementById('offer_type');
	const discountType = document.getElementById('discount_type');
	const discountValue = document.getElementById('discount_value');
	const discountUnit = document.getElementById('discount_unit');
	const discountHelp = document.getElementById('discount_help');
	const maxDiscountField = document.getElementById('max_discount_field');
	
	const productSelection = document.getElementById('product_selection');
	const categorySelection = document.getElementById('category_selection');
	const brandSelection = document.getElementById('brand_selection');
	const userSelection = document.getElementById('user_selection');
	const billingAmountField = document.getElementById('billing_amount_field');
	
	const userSegment = document.getElementById('user_segment');
	const minimumPurchaseAmountField = document.getElementById('minimum_purchase_amount_field');

	function toggleOfferTypeFields() {
		const selectedType = offerType.value;
		
		// Hide all selection fields
		productSelection.style.display = 'none';
		categorySelection.style.display = 'none';
		brandSelection.style.display = 'none';
		userSelection.style.display = 'none';
		billingAmountField.style.display = 'none';
		
		// Show relevant field based on offer type
		switch(selectedType) {
			case 'product':
				productSelection.style.display = 'block';
				break;
			case 'category':
				categorySelection.style.display = 'block';
				break;
			case 'brand':
				brandSelection.style.display = 'block';
				break;
			case 'user':
				userSelection.style.display = 'block';
				break;
			case 'billing_amount':
				billingAmountField.style.display = 'block';
				break;
		}
	}

	function toggleDiscountFields() {
		const selectedDiscountType = discountType.value;
		
		if (selectedDiscountType === 'percentage') {
			discountUnit.textContent = '%';
			discountHelp.textContent = 'Enter percentage (0-100)';
			discountValue.setAttribute('max', '100');
			maxDiscountField.style.display = 'block';
		} else {
			discountUnit.textContent = '{{ $currencySymbol ?? '$' }}';
			discountHelp.textContent = 'Enter fixed discount amount';
			discountValue.removeAttribute('max');
			maxDiscountField.style.display = 'none';
		}
	}

	function toggleUserSegmentFields() {
		const selectedSegment = userSegment.value;
		
		if (selectedSegment === 'minimum_purchase') {
			minimumPurchaseAmountField.style.display = 'block';
		} else {
			minimumPurchaseAmountField.style.display = 'none';
		}
	}

	// Initial setup
	toggleOfferTypeFields();
	toggleDiscountFields();
	toggleUserSegmentFields();

	// Event listeners
	offerType.addEventListener('change', toggleOfferTypeFields);
	discountType.addEventListener('change', toggleDiscountFields);
	userSegment.addEventListener('change', toggleUserSegmentFields);
});
</script>
@endsection

