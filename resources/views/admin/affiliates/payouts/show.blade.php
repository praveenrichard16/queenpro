@extends('layouts.admin')

@section('title', 'Payout Details')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Payout Details</h6>
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
			<li class="fw-medium text-secondary-light">Details</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-24">Payout Information</h6>
					
					<form method="POST" action="{{ route('admin.affiliates.payouts.update', $payout) }}" class="row g-4">
						@csrf
						@method('PUT')

						<div class="col-lg-6">
							<label class="form-label text-secondary-light">Affiliate</label>
							<input type="text" value="{{ $payout->affiliate->user->name }} ({{ $payout->affiliate->user->email }})" class="form-control bg-neutral-50 radius-12 h-56-px" disabled>
						</div>

						<div class="col-lg-6">
							<label class="form-label text-secondary-light">Amount</label>
							<input type="text" value="{{ \App\Services\CurrencyService::format($payout->total_amount) }}" class="form-control bg-neutral-50 radius-12 h-56-px" disabled>
						</div>

						<div class="col-lg-6">
							<label class="form-label text-secondary-light">Status <span class="text-danger">*</span></label>
							<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px @error('status') is-invalid @enderror" required>
								<option value="pending" @selected($payout->status === 'pending')>Pending</option>
								<option value="processing" @selected($payout->status === 'processing')>Processing</option>
								<option value="paid" @selected($payout->status === 'paid')>Paid</option>
								<option value="failed" @selected($payout->status === 'failed')>Failed</option>
							</select>
							@error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
						</div>

						<div class="col-lg-6">
							<label class="form-label text-secondary-light">Payment Method</label>
							<input type="text" value="{{ $payout->payment_method ?? '—' }}" class="form-control bg-neutral-50 radius-12 h-56-px" disabled>
						</div>

						<div class="col-lg-6">
							<label class="form-label text-secondary-light">Transaction ID</label>
							<input type="text" name="transaction_id" value="{{ old('transaction_id', $payout->transaction_id) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('transaction_id') is-invalid @enderror" placeholder="Enter transaction ID">
							@error('transaction_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
						</div>

						<div class="col-12">
							<label class="form-label text-secondary-light">Payment Details</label>
							<textarea rows="3" class="form-control bg-neutral-50 radius-12" disabled>{{ $payout->payment_details ?? '—' }}</textarea>
						</div>

						<div class="col-12">
							<label class="form-label text-secondary-light">Notes</label>
							<textarea name="notes" rows="3" class="form-control bg-neutral-50 radius-12 @error('notes') is-invalid @enderror" placeholder="Additional notes">{{ old('notes', $payout->notes) }}</textarea>
							@error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
						</div>

						<div class="col-12 d-flex gap-3 mt-12">
							<button class="btn btn-primary radius-12 px-24">Update Payout</button>
							<a href="{{ route('admin.affiliates.payouts.index') }}" class="btn btn-outline-secondary radius-12 px-24">Back</a>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-24">Payout Summary</h6>
					<div class="d-flex flex-column gap-3">
						<div>
							<p class="text-secondary-light small mb-1">Created</p>
							<p class="fw-semibold mb-0">{{ $payout->created_at->format('M d, Y H:i') }}</p>
						</div>
						@if($payout->paid_at)
						<div>
							<p class="text-secondary-light small mb-1">Paid At</p>
							<p class="fw-semibold mb-0">{{ $payout->paid_at->format('M d, Y H:i') }}</p>
						</div>
						@endif
						<div>
							<p class="text-secondary-light small mb-1">Status</p>
							<span class="px-20 py-6 rounded-pill fw-semibold text-sm 
								{{ $payout->status === 'paid' ? 'bg-success-focus text-success-main' : 
								   ($payout->status === 'processing' ? 'bg-info-focus text-info-main' : 
								   ($payout->status === 'pending' ? 'bg-warning-focus text-warning-main' : 'bg-danger-focus text-danger-main')) }}">
								{{ ucfirst($payout->status) }}
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

