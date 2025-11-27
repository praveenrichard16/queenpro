@extends('layouts.admin')

@section('title', $affiliate->exists ? 'Edit Affiliate' : 'Add Affiliate')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $affiliate->exists ? 'Edit Affiliate' : 'Add Affiliate' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.affiliates.index') }}" class="hover-text-primary">Affiliates</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $affiliate->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $affiliate->exists ? route('admin.affiliates.update', $affiliate) : route('admin.affiliates.store') }}" class="row g-4">
				@csrf
				@if($affiliate->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">User <span class="text-danger">*</span></label>
					<select name="user_id" class="form-select bg-neutral-50 radius-12 h-56-px @error('user_id') is-invalid @enderror" required {{ $affiliate->exists ? 'disabled' : '' }}>
						<option value="">Select user</option>
						@foreach($users as $user)
							<option value="{{ $user->id }}" @selected(old('user_id', $affiliate->user_id) == $user->id)>
								{{ $user->name }} ({{ $user->email }})
							</option>
						@endforeach
					</select>
					@if($affiliate->exists)
						<input type="hidden" name="user_id" value="{{ $affiliate->user_id }}">
					@endif
					@error('user_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				@if($affiliate->exists)
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Affiliate Code</label>
					<input type="text" value="{{ $affiliate->affiliate_code }}" class="form-control bg-neutral-50 radius-12 h-56-px" disabled>
					<div class="form-text mt-1">Referral URL: <a href="{{ $affiliate->referral_url }}" target="_blank">{{ $affiliate->referral_url }}</a></div>
				</div>
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Commission Rate (%) <span class="text-danger">*</span></label>
					<input type="number" step="0.01" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', $affiliate->commission_rate ?? 10) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('commission_rate') is-invalid @enderror" required>
					@error('commission_rate') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Status <span class="text-danger">*</span></label>
					<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px @error('status') is-invalid @enderror" required>
						<option value="pending" @selected(old('status', $affiliate->status) === 'pending')>Pending</option>
						<option value="active" @selected(old('status', $affiliate->status) === 'active')>Active</option>
						<option value="suspended" @selected(old('status', $affiliate->status) === 'suspended')>Suspended</option>
					</select>
					@error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Payment Information (JSON)</label>
					<textarea name="payment_info" rows="4" class="form-control bg-neutral-50 radius-12 @error('payment_info') is-invalid @enderror" placeholder='{"bank_name": "Bank Name", "account_number": "123456", "paypal_email": "email@example.com"}'>{{ old('payment_info', $affiliate->payment_info ? json_encode($affiliate->payment_info, JSON_PRETTY_PRINT) : '') }}</textarea>
					@error('payment_info') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Enter payment information as JSON format.</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Notes</label>
					<textarea name="notes" rows="3" class="form-control bg-neutral-50 radius-12 @error('notes') is-invalid @enderror" placeholder="Additional notes">{{ old('notes', $affiliate->notes) }}</textarea>
					@error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $affiliate->exists ? 'Update Affiliate' : 'Create Affiliate' }}</button>
					<a href="{{ route('admin.affiliates.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

