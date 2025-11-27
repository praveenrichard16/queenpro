@extends('layouts.admin')

@section('title', 'Edit Quotation')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Edit Quotation</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.quotations.index') }}" class="hover-text-primary">Quotations</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Edit</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.quotations.update', $quotation) }}" class="row g-4" id="quotationForm">
				@csrf
				@method('PUT')

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Quotation Information</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Lead <span class="text-danger">*</span></label>
					<select name="lead_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('lead_id') is-invalid @enderror" required>
						<option value="">Select Lead</option>
						@foreach($leads as $lead)
							<option value="{{ $lead->id }}" @selected(old('lead_id', $quotation->lead_id) == $lead->id)>{{ $lead->name }} ({{ $lead->email ?? 'No email' }})</option>
						@endforeach
					</select>
					@error('lead_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Quote Number</label>
					<input type="text" name="quote_number" value="{{ old('quote_number', $quotation->quote_number) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('quote_number') is-invalid @enderror">
					@error('quote_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Status <span class="text-danger">*</span></label>
					<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px @error('status') is-invalid @enderror" required>
						<option value="draft" @selected(old('status', $quotation->status) === 'draft')>Draft</option>
						<option value="sent" @selected(old('status', $quotation->status) === 'sent')>Sent</option>
						<option value="accepted" @selected(old('status', $quotation->status) === 'accepted')>Accepted</option>
						<option value="rejected" @selected(old('status', $quotation->status) === 'rejected')>Rejected</option>
						<option value="expired" @selected(old('status', $quotation->status) === 'expired')>Expired</option>
					</select>
					@error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Currency <span class="text-danger">*</span></label>
					<input type="text" name="currency" value="{{ old('currency', $quotation->currency ?? currency_code()) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('currency') is-invalid @enderror" required maxlength="3">
					@error('currency') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Valid Until</label>
					<input type="date" name="valid_until" value="{{ old('valid_until', $quotation->valid_until?->format('Y-m-d')) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('valid_until') is-invalid @enderror">
					@error('valid_until') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Quotation Items</h6>
				</div>

				<div class="col-12" id="itemsContainer">
					@foreach($quotation->items as $index => $item)
						<div class="item-row mb-3 p-3 border rounded">
							<div class="row g-3">
								<div class="col-lg-5">
									<label class="form-label text-secondary-light">Product <span class="text-danger">*</span></label>
									<select name="items[{{ $index }}][product_id]" class="form-select bg-neutral-50 radius-12 h-56-px" required>
										<option value="">Select Product</option>
										@foreach($products as $product)
											<option value="{{ $product->id }}" data-price="{{ $product->effective_price }}" @selected(old("items.$index.product_id", $item->product_id) == $product->id)>{{ $product->name }} - {{ currency($product->effective_price) }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-2">
									<label class="form-label text-secondary-light">Quantity <span class="text-danger">*</span></label>
									<input type="number" name="items[{{ $index }}][quantity]" value="{{ old("items.$index.quantity", $item->quantity) }}" min="1" class="form-control bg-neutral-50 radius-12 h-56-px item-quantity" required>
								</div>
								<div class="col-lg-2">
									<label class="form-label text-secondary-light">Price <span class="text-danger">*</span></label>
									<input type="number" name="items[{{ $index }}][price]" value="{{ old("items.$index.price", $item->price) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px item-price" required>
								</div>
								<div class="col-lg-2">
									<label class="form-label text-secondary-light">Tax Amount</label>
									<input type="number" name="items[{{ $index }}][tax_amount]" value="{{ old("items.$index.tax_amount", $item->tax_amount) }}" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px item-tax">
								</div>
								<div class="col-lg-1 d-flex align-items-end">
									<button type="button" class="btn btn-danger btn-sm remove-item" @if($quotation->items->count() == 1) style="display:none;" @endif>
										<iconify-icon icon="solar:trash-bin-outline"></iconify-icon>
									</button>
								</div>
							</div>
						</div>
					@endforeach
				</div>

				<div class="col-12">
					<button type="button" class="btn btn-outline-primary" id="addItem">Add Item</button>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Notes</h6>
				</div>

				<div class="col-12">
					<textarea name="notes" rows="4" class="form-control bg-neutral-50 radius-12 @error('notes') is-invalid @enderror">{{ old('notes', $quotation->notes) }}</textarea>
					@error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="d-flex gap-2">
						<button type="submit" class="btn btn-primary radius-12 h-56-px">Update Quotation</button>
						<a href="{{ route('admin.quotations.index') }}" class="btn btn-outline-secondary radius-12 h-56-px">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

@push('scripts')
<script>
	let itemIndex = {{ $quotation->items->count() }};
	
	document.getElementById('addItem').addEventListener('click', function() {
		const container = document.getElementById('itemsContainer');
		const newItem = container.firstElementChild.cloneNode(true);
		
		// Update indices
		newItem.querySelectorAll('select, input').forEach(input => {
			if (input.name) {
				input.name = input.name.replace(/\[\d+\]/, '[' + itemIndex + ']');
				if (input.type !== 'hidden') {
					input.value = '';
				}
			}
		});
		
		newItem.querySelector('.remove-item').style.display = 'block';
		container.appendChild(newItem);
		itemIndex++;
	});
	
	document.addEventListener('click', function(e) {
		if (e.target.closest('.remove-item')) {
			if (document.querySelectorAll('.item-row').length > 1) {
				e.target.closest('.item-row').remove();
			}
		}
	});
	
	// Auto-fill price when product is selected
	document.addEventListener('change', function(e) {
		if (e.target.matches('select[name*="[product_id]"]')) {
			const option = e.target.options[e.target.selectedIndex];
			const price = option.dataset.price;
			const priceInput = e.target.closest('.item-row').querySelector('.item-price');
			if (price && priceInput && !priceInput.value) {
				priceInput.value = price;
			}
		}
	});
</script>
@endpush
@endsection

