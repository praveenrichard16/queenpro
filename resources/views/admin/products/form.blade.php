@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $product->exists ? 'Edit Product' : 'Add Product' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.products.index') }}" class="hover-text-primary">Products</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $product->exists ? 'Edit' : 'Add' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form id="product-form" method="POST" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="row g-4">
				@csrf
				@if($product->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name</label>
					<input type="text" name="name" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Slug <span class="text-muted small">(auto-generated if empty)</span></label>
					<input type="text" name="slug" class="form-control bg-neutral-50 radius-12 h-56-px @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}" placeholder="product-slug">
					@error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Leave empty to auto-generate from name</div>
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Categories</label>
					<div class="d-flex gap-2">
						<select id="categorySelect" class="form-select bg-neutral-50 radius-12 h-56-px">
							<option value="">Select category</option>
							@foreach($categories as $id => $name)
								<option value="{{ $id }}" data-name="{{ $name }}">{{ $name }}</option>
							@endforeach
						</select>
						<button type="button" id="addCategoryBtn" class="btn btn-primary h-56-px radius-12 d-inline-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						</button>
						<a href="{{ route('admin.categories.create') }}" class="btn btn-outline-secondary h-56-px radius-12 d-inline-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						</a>
					</div>
					<div id="selectedCategories" class="mt-2 d-flex flex-wrap gap-2">
						@if($product->exists && $product->categories->isNotEmpty())
							@foreach($product->categories as $category)
								<span class="badge bg-primary d-inline-flex align-items-center gap-2" data-id="{{ $category->id }}">
									{{ $category->name }}
									<button type="button" class="btn-close btn-close-white" style="font-size: 0.7em;" onclick="removeCategory({{ $category->id }})"></button>
									<input type="hidden" name="categories[]" value="{{ $category->id }}" form="product-form">
								</span>
							@endforeach
						@endif
					</div>
					@error('categories') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					@error('categories.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-lg-6 d-flex gap-2 align-items-end">
					<div class="flex-grow-1">
						<label class="form-label text-secondary-light">Brand</label>
						<select name="brand_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('brand_id') is-invalid @enderror">
							<option value="">Select brand (optional)</option>
							@foreach($brands as $brand)
								<option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id)==$brand->id)>{{ $brand->name }}</option>
							@endforeach
						</select>
						@error('brand_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					</div>
					<a href="{{ route('admin.brands.create') }}" class="btn btn-outline-secondary h-56-px radius-12 d-inline-flex align-items-center justify-content-center">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					</a>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Short Description</label>
					<textarea name="short_description" rows="2" class="form-control bg-neutral-50 radius-12 @error('short_description') is-invalid @enderror" placeholder="Brief product summary for listings">{{ old('short_description', $product->short_description) }}</textarea>
					@error('short_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Short summary shown on product listings and overviews</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Long Description</label>
					<textarea name="long_description" rows="6" class="form-control bg-neutral-50 radius-12 @error('long_description') is-invalid @enderror" placeholder="Detailed product information">{{ old('long_description', $product->long_description) }}</textarea>
					@error('long_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Detailed description shown on product detail page</div>
				</div>

				<div class="col-12 mb-4">
					<label class="form-label text-secondary-light">Specification</label>
					<div class="quill-editor bg-neutral-50 radius-12 @error('specification') border border-danger @enderror" id="product-specification-editor" style="min-height:260px;">
						{!! old('specification', $product->specification) !!}
					</div>
					<input type="hidden" name="specification" id="product-specification-content" value="{{ old('specification', $product->specification) }}">
					@error('specification') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Product specifications shown on product detail page specification section</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Description (Legacy)</label>
					<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 @error('description') is-invalid @enderror" placeholder="Legacy description field (optional)">{{ old('description', $product->description) }}</textarea>
					@error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Legacy field for backward compatibility (optional)</div>
				</div>

				<div class="col-md-4">
					<label class="form-label text-secondary-light">Price</label>
					<input type="number" step="0.01" min="0" name="price" class="form-control bg-neutral-50 radius-12 h-56-px @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
					@error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-4">
					<label class="form-label text-secondary-light">Selling Price</label>
					<input type="number" step="0.01" min="0" name="selling_price" class="form-control bg-neutral-50 radius-12 h-56-px @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', $product->selling_price) }}">
					@error('selling_price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-4">
					<label class="form-label text-secondary-light">Stock Quantity</label>
					<input type="number" min="0" name="stock_quantity" class="form-control bg-neutral-50 radius-12 h-56-px @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
					@error('stock_quantity') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Tax Class</label>
					<select name="tax_class_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('tax_class_id') is-invalid @enderror" id="tax_class_select">
						<option value="">No tax class (use product-specific rate)</option>
						@if(isset($taxClasses) && $taxClasses->isNotEmpty())
							@foreach($taxClasses as $taxClass)
								<option value="{{ $taxClass->id }}" @selected(old('tax_class_id', $product->tax_class_id) == $taxClass->id)>
									{{ $taxClass->name }} ({{ number_format($taxClass->rate ?? 0, 2) }}%)
								</option>
							@endforeach
						@endif
					</select>
					@error('tax_class_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Select a tax class or leave empty to use product-specific tax rate.</div>
				</div>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Tax Rate (%)</label>
					<div class="input-group">
						<input type="number" step="0.01" min="0" max="100" name="tax_rate" class="form-control bg-neutral-50 radius-12 h-56-px @error('tax_rate') is-invalid @enderror" value="{{ old('tax_rate', $product->tax_rate) }}" id="tax_rate_input" placeholder="0.00">
						<span class="input-group-text bg-neutral-50">%</span>
					</div>
					@error('tax_rate') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Product-specific tax rate (used if no tax class is selected).</div>
				</div>
				<div class="col-md-4">
					<label class="form-label text-secondary-light">Status</label>
					<select name="is_active" class="form-select bg-neutral-50 radius-12 h-56-px">
						<option value="1" @selected(old('is_active', $product->is_active))>Active</option>
						<option value="0" @selected(!old('is_active', $product->is_active))>Inactive</option>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label text-secondary-light">Featured Product</label>
					<div class="form-check form-switch mt-2">
						<input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured" @checked(old('is_featured', $product->is_featured ?? false))>
						<label class="form-check-label text-secondary-light" for="is_featured">
							Mark as featured
						</label>
					</div>
					@error('is_featured') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-md-4">
					<label class="form-label text-secondary-light">Enable Countdown Timer</label>
					<div class="form-check form-switch mt-2">
						<input type="checkbox" name="enable_countdown_timer" value="1" class="form-check-input" id="enable_countdown_timer" @checked(old('enable_countdown_timer', $product->enable_countdown_timer ?? false))>
						<label class="form-check-label text-secondary-light" for="enable_countdown_timer">
							Show countdown timer on product page
						</label>
					</div>
					@error('enable_countdown_timer') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-md-4" id="countdown_timer_end_wrapper" style="display: {{ old('enable_countdown_timer', $product->enable_countdown_timer) ? 'block' : 'none' }};">
					<label class="form-label text-secondary-light">Countdown Timer End Date & Time</label>
					<input type="datetime-local" name="countdown_timer_end" class="form-control bg-neutral-50 radius-12 h-56-px @error('countdown_timer_end') is-invalid @enderror" value="{{ old('countdown_timer_end', $product->countdown_timer_end ? $product->countdown_timer_end->format('Y-m-d\TH:i') : '') }}">
					@error('countdown_timer_end') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">When should the countdown timer end?</div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Primary Image</label>
					<input type="file" name="image" class="form-control bg-neutral-50 radius-12 h-56-px @error('image') is-invalid @enderror">
					@error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload JPG/PNG/WebP exactly 1080×1080 px, max 2MB.</div>
				</div>
				@if($product->image)
					<div class="col-lg-6">
						<label class="form-label text-secondary-light d-block">Current Image</label>
						<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex">
							<img src="{{ asset(ltrim($product->image, '/')) }}" class="rounded" style="max-height:140px" alt="Product image">
						</div>
					</div>
				@endif

				<div class="col-md-12">
					<label class="form-label text-secondary-light">Gallery Images</label>
					<input type="file" name="gallery[]" class="form-control bg-neutral-50 radius-12 @error('gallery.*') is-invalid @enderror" multiple>
					@error('gallery') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					@error('gallery.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Upload JPG/PNG/WebP images (recommended: 400×400 px or smaller for thumbnails), max 2MB each.</div>
				</div>

				@if($product->exists && $product->images?->isNotEmpty())
					<div class="col-12">
						<label class="form-label text-secondary-light d-block">Existing Gallery</label>
						<div class="d-flex flex-wrap gap-3">
							@foreach($product->images as $image)
								<div class="position-relative border radius-12 bg-neutral-50 p-12" style="width:140px;">
									<img src="{{ asset(ltrim($image->path, '/')) }}" alt="Gallery image" class="img-fluid rounded mb-2">
									<button type="submit" class="btn btn-outline-danger btn-sm radius-12" form="delete-product-image-{{ $image->id }}" onclick="return confirm('Remove this image?')">
										Remove
									</button>
								</div>
							@endforeach
						</div>
					</div>
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Tags</label>
					<div class="d-flex gap-2">
						<select id="tagSelect" class="form-select bg-neutral-50 radius-12 h-56-px">
							<option value="">Select tag</option>
							@foreach($tags as $tag)
								<option value="{{ $tag->id }}" data-name="{{ $tag->name }}">{{ $tag->name }}</option>
							@endforeach
						</select>
						<button type="button" id="addTagBtn" class="btn btn-primary h-56-px radius-12 d-inline-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						</button>
						<a href="{{ route('admin.tags.create') }}" class="btn btn-outline-secondary h-56-px radius-12 d-inline-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						</a>
					</div>
					<div id="selectedTags" class="mt-2 d-flex flex-wrap gap-2">
						@if($product->exists && $product->tags->isNotEmpty())
							@foreach($product->tags as $tag)
								<span class="badge bg-info d-inline-flex align-items-center gap-2" data-id="{{ $tag->id }}">
									{{ $tag->name }}
									<button type="button" class="btn-close btn-close-white" style="font-size: 0.7em;" onclick="removeTag({{ $tag->id }})"></button>
									<input type="hidden" name="tags[]" value="{{ $tag->id }}" form="product-form">
								</span>
							@endforeach
						@endif
					</div>
					@error('tags') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					@error('tags.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-3">Product Attributes</h6>
					@if(isset($attributes) && $attributes->isNotEmpty())
						<div class="row g-3">
							@foreach($attributes as $attribute)
								<div class="col-lg-6">
									<label class="form-label text-secondary-light">{{ $attribute->name }}</label>
									@php
										$existingAttribute = $product->exists ? $product->attributes->where('id', $attribute->id)->first() : null;
										$existingValueId = old("attributes.{$attribute->id}.attribute_value_id", $existingAttribute?->pivot->attribute_value_id);
										$existingCustomValue = old("attributes.{$attribute->id}.custom_value", $existingAttribute?->pivot->custom_value);
									@endphp
									@if($attribute->type === 'select')
										<select name="attributes[{{ $attribute->id }}][attribute_value_id]" class="form-select bg-neutral-50 radius-12 h-56-px">
											<option value="">— None —</option>
											@foreach($attribute->values as $value)
												<option value="{{ $value->id }}" @selected($existingValueId == $value->id)>
													{{ $value->display_value ?? $value->value }}
												</option>
											@endforeach
										</select>
										<input type="hidden" name="attributes[{{ $attribute->id }}][attribute_id]" value="{{ $attribute->id }}">
									@elseif($attribute->type === 'text')
										<input type="text" name="attributes[{{ $attribute->id }}][custom_value]" value="{{ $existingCustomValue ?? '' }}" class="form-control bg-neutral-50 radius-12 h-56-px" placeholder="Enter custom value">
										<input type="hidden" name="attributes[{{ $attribute->id }}][attribute_id]" value="{{ $attribute->id }}">
									@elseif($attribute->type === 'color')
										<select name="attributes[{{ $attribute->id }}][attribute_value_id]" class="form-select bg-neutral-50 radius-12 h-56-px">
											<option value="">— None —</option>
											@foreach($attribute->values as $value)
												<option value="{{ $value->id }}" @selected($existingValueId == $value->id) style="background-color: {{ $value->color_code ?? '#000' }}; color: white;">
													{{ $value->display_value ?? $value->value }}
												</option>
											@endforeach
										</select>
										<input type="hidden" name="attributes[{{ $attribute->id }}][attribute_id]" value="{{ $attribute->id }}">
									@elseif($attribute->type === 'image')
										<select name="attributes[{{ $attribute->id }}][attribute_value_id]" class="form-select bg-neutral-50 radius-12 h-56-px">
											<option value="">— None —</option>
											@foreach($attribute->values as $value)
												<option value="{{ $value->id }}" @selected($existingValueId == $value->id)>
													{{ $value->display_value ?? $value->value }}
												</option>
											@endforeach
										</select>
										<input type="hidden" name="attributes[{{ $attribute->id }}][attribute_id]" value="{{ $attribute->id }}">
									@endif
								</div>
							@endforeach
						</div>
					@else
						<p class="text-secondary-light">No attributes available. <a href="{{ route('admin.attributes.create') }}">Create one</a></p>
					@endif
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Meta Title</label>
					<input type="text" name="meta_title" class="form-control bg-neutral-50 radius-12 @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $product->meta_title) }}" placeholder="SEO meta title">
					@error('meta_title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="col-12">
					<label class="form-label text-secondary-light">Meta Description</label>
					<textarea name="meta_description" rows="3" class="form-control bg-neutral-50 radius-12 @error('meta_description') is-invalid @enderror" placeholder="SEO meta description">{{ old('meta_description', $product->meta_description) }}</textarea>
					@error('meta_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-2 mt-8">
					<button type="submit" class="btn btn-primary radius-12 px-24">{{ $product->exists ? 'Update Product' : 'Create Product' }}</button>
					<a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

@if($product->exists && $product->images?->isNotEmpty())
	@foreach($product->images as $image)
		<form id="delete-product-image-{{ $image->id }}" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" method="POST" class="d-none">
			@csrf
			@method('DELETE')
		</form>
	@endforeach
@endif

<script>
// Wrap all code in DOMContentLoaded to ensure DOM elements exist
document.addEventListener('DOMContentLoaded', function() {
	// Tags Management
	const selectedTagIds = new Set(@json($product->exists ? $product->tags->pluck('id')->all() : []));
	
	const addTagBtn = document.getElementById('addTagBtn');
	if (addTagBtn) {
		addTagBtn.addEventListener('click', function() {
			const select = document.getElementById('tagSelect');
			const tagId = select.value;
			const tagName = select.options[select.selectedIndex].dataset.name;
			
			if (!tagId || selectedTagIds.has(parseInt(tagId))) {
				return;
			}
			
			selectedTagIds.add(parseInt(tagId));
			
			const container = document.getElementById('selectedTags');
			const badge = document.createElement('span');
			badge.className = 'badge bg-info d-inline-flex align-items-center gap-2';
			badge.dataset.id = tagId;
			badge.innerHTML = `
				${tagName}
				<button type="button" class="btn-close btn-close-white" style="font-size: 0.7em;" onclick="removeTag(${tagId})"></button>
				<input type="hidden" name="tags[]" value="${tagId}" form="product-form">
			`;
			container.appendChild(badge);
			
			select.value = '';
		});
	}
	
	window.removeTag = function(tagId) {
		selectedTagIds.delete(tagId);
		const badge = document.querySelector(`#selectedTags [data-id="${tagId}"]`);
		if (badge) {
			badge.remove();
		}
	};
	
	// Categories Management
	const selectedCategoryIds = new Set(@json($product->exists ? $product->categories->pluck('id')->all() : []));
	
	const addCategoryBtn = document.getElementById('addCategoryBtn');
	if (addCategoryBtn) {
		addCategoryBtn.addEventListener('click', function() {
			const select = document.getElementById('categorySelect');
			const categoryId = select.value;
			const categoryName = select.options[select.selectedIndex].dataset.name;
			
			if (!categoryId || selectedCategoryIds.has(parseInt(categoryId))) {
				return;
			}
			
			selectedCategoryIds.add(parseInt(categoryId));
			
			const container = document.getElementById('selectedCategories');
			const badge = document.createElement('span');
			badge.className = 'badge bg-primary d-inline-flex align-items-center gap-2';
			badge.dataset.id = categoryId;
			badge.innerHTML = `
				${categoryName}
				<button type="button" class="btn-close btn-close-white" style="font-size: 0.7em;" onclick="removeCategory(${categoryId})"></button>
				<input type="hidden" name="categories[]" value="${categoryId}" form="product-form">
			`;
			container.appendChild(badge);
			
			select.value = '';
		});
	}
	
	window.removeCategory = function(categoryId) {
		selectedCategoryIds.delete(categoryId);
		const badge = document.querySelector(`#selectedCategories [data-id="${categoryId}"]`);
		if (badge) {
			badge.remove();
		}
	};
	
	// Tax Class Management - Auto-populate tax rate when tax class is selected
	const taxClassSelect = document.getElementById('tax_class_select');
	if (taxClassSelect) {
		taxClassSelect.addEventListener('change', function() {
			const taxClassId = this.value;
			const taxRateInput = document.getElementById('tax_rate_input');
			
			if (taxClassId) {
				// Get the selected option's text which contains the rate
				const selectedOption = this.options[this.selectedIndex];
				const rateMatch = selectedOption.text.match(/\(([\d.]+)%\)/);
				
				if (rateMatch && taxRateInput) {
					// Disable the tax rate input when a tax class is selected
					taxRateInput.disabled = true;
					taxRateInput.value = rateMatch[1];
				}
			} else {
				// Enable the tax rate input when no tax class is selected
				if (taxRateInput) {
					taxRateInput.disabled = false;
					if (!taxRateInput.value) {
						taxRateInput.value = '';
					}
				}
			}
		});
		
		// Initialize tax rate input state on page load
		const taxRateInput = document.getElementById('tax_rate_input');
		if (taxRateInput && taxClassSelect.value) {
			taxRateInput.disabled = true;
		}
	}
});

// Countdown Timer Toggle
document.addEventListener('DOMContentLoaded', function() {
	const enableTimerCheckbox = document.getElementById('enable_countdown_timer');
	const timerEndWrapper = document.getElementById('countdown_timer_end_wrapper');
	
	if (enableTimerCheckbox && timerEndWrapper) {
		enableTimerCheckbox.addEventListener('change', function() {
			timerEndWrapper.style.display = this.checked ? 'block' : 'none';
			if (!this.checked) {
				// Clear the value when disabled
				const timerEndInput = timerEndWrapper.querySelector('input[name="countdown_timer_end"]');
				if (timerEndInput) {
					timerEndInput.value = '';
				}
			}
		});
	}
});
</script>

@push('scripts')
<script src="{{ asset('wowdash/assets/js/editor.quill.js') }}"></script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const editorElement = document.getElementById('product-specification-editor');
		const hiddenInput = document.getElementById('product-specification-content');

		if (!editorElement || !hiddenInput || typeof Quill === 'undefined') {
			return;
		}

		// Create custom toolbar with table button
		const toolbarOptions = [
			[{ 'header': [1, 2, 3, false] }],
			['bold', 'italic', 'underline', 'strike'],
			[{ 'color': [] }, { 'background': [] }],
			[{ 'align': [] }],
			[{ 'list': 'ordered'}, { 'list': 'bullet' }],
			[{ 'indent': '-1'}, { 'indent': '+1' }],
			['blockquote', 'code-block'],
			['link', 'image'],
			['table'],
			['clean']
		];

		const quill = new Quill(editorElement, {
			theme: 'snow',
			placeholder: 'Enter product specifications here...',
			modules: {
				toolbar: {
					container: toolbarOptions,
					handlers: {
						'table': function() {
							const table = prompt('Enter table dimensions (e.g., "3x4" for 3 columns, 4 rows):', '3x3');
							if (!table) return;
							
							const [cols, rows] = table.split('x').map(n => parseInt(n) || 3);
							if (cols < 1 || cols > 10 || rows < 1 || rows > 20) {
								alert('Please enter valid dimensions (1-10 columns, 1-20 rows)');
								return;
							}

							let tableHTML = '<table style="border-collapse: collapse; width: 100%; margin: 10px 0;"><tbody>';
							for (let r = 0; r < rows; r++) {
								tableHTML += '<tr>';
								for (let c = 0; c < cols; c++) {
									tableHTML += '<td style="border: 1px solid #ddd; padding: 8px;">&nbsp;</td>';
								}
								tableHTML += '</tr>';
							}
							tableHTML += '</tbody></table>';

							const range = quill.getSelection(true);
							if (range) {
								quill.clipboard.dangerouslyPasteHTML(range.index, tableHTML);
							}
						}
					}
				}
			}
		});

		// Add custom table button styling and icon
		const style = document.createElement('style');
		style.textContent = `
			.ql-toolbar button.ql-table::before {
				content: "📊";
				font-size: 16px;
			}
			.ql-toolbar button.ql-table {
				width: 28px;
			}
			.ql-editor table {
				border-collapse: collapse;
				width: 100%;
				margin: 10px 0;
			}
			.ql-editor table td,
			.ql-editor table th {
				border: 1px solid #ddd;
				padding: 8px;
				min-width: 50px;
			}
			.ql-editor table th {
				background-color: #f2f2f2;
				font-weight: bold;
			}
		`;
		document.head.appendChild(style);

		quill.on('text-change', function () {
			hiddenInput.value = quill.root.innerHTML;
		});

		const initialContent = hiddenInput.value;
		if (initialContent) {
			quill.root.innerHTML = initialContent;
		}

		const productForm = document.getElementById('product-form');
		if (productForm) {
			productForm.addEventListener('submit', function () {
				hiddenInput.value = quill.root.innerHTML;
				console.log('Product form submitting - Quill content saved');
			});
		}
	});
</script>
@endpush
@endsection
