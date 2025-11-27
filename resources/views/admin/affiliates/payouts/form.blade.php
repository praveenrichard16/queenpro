@extends('layouts.admin')

@section('title', 'Create Payout')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Create Payout</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.affiliates.payouts.index') }}" class="hover-text-primary">Payouts</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Create</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('admin.affiliates.payouts.store') }}" class="row g-4" id="payoutForm">
				@csrf

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Affiliate <span class="text-danger">*</span></label>
					<select name="affiliate_id" id="affiliateSelect" class="form-select bg-neutral-50 radius-12 h-56-px @error('affiliate_id') is-invalid @enderror" required>
						<option value="">Select affiliate</option>
						@foreach($affiliates as $affiliate)
							<option value="{{ $affiliate->id }}" data-pending="{{ $affiliate->pending_earnings }}">
								{{ $affiliate->user->name }} - Pending: {{ \App\Services\CurrencyService::format($affiliate->pending_earnings) }}
							</option>
						@endforeach
					</select>
					@error('affiliate_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Amount <span class="text-danger">*</span></label>
					<input type="number" step="0.01" min="0.01" name="total_amount" id="totalAmount" value="{{ old('total_amount') }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('total_amount') is-invalid @enderror" required>
					@error('total_amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1" id="maxAmount">Max: <span class="fw-semibold">0.00</span></div>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Payment Method <span class="text-danger">*</span></label>
					<select name="payment_method" class="form-select bg-neutral-50 radius-12 h-56-px @error('payment_method') is-invalid @enderror" required>
						<option value="">Select method</option>
						<option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer</option>
						<option value="paypal" @selected(old('payment_method') === 'paypal')>PayPal</option>
						<option value="stripe" @selected(old('payment_method') === 'stripe')>Stripe</option>
						<option value="other" @selected(old('payment_method') === 'other')>Other</option>
					</select>
					@error('payment_method') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Payment Details</label>
					<textarea name="payment_details" rows="3" class="form-control bg-neutral-50 radius-12 @error('payment_details') is-invalid @enderror" placeholder="Bank account details, PayPal email, transaction reference, etc.">{{ old('payment_details') }}</textarea>
					@error('payment_details') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Notes</label>
					<textarea name="notes" rows="2" class="form-control bg-neutral-50 radius-12 @error('notes') is-invalid @enderror" placeholder="Additional notes">{{ old('notes') }}</textarea>
					@error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">Create Payout</button>
					<a href="{{ route('admin.affiliates.payouts.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
document.getElementById('affiliateSelect').addEventListener('change', function() {
	const selected = this.options[this.selectedIndex];
	const pending = parseFloat(selected.dataset.pending || 0);
	document.getElementById('totalAmount').max = pending;
	document.querySelector('#maxAmount span').textContent = pending.toFixed(2);
});
</script>
@endsection

