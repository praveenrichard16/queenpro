@extends('layouts.customer')

@section('title', 'My Addresses')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Saved Addresses</h6>
			<p class="text-secondary-light mb-0">Manage your billing and shipping destinations for faster checkout.</p>
		</div>
		<a href="{{ route('customer.addresses.create') }}" class="btn btn-primary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:map-point-wave-outline" class="text-lg"></iconify-icon>
			Add New Address
		</a>
	</div>

	@if($addresses->isEmpty())
		<div class="card border-0 shadow-sm">
			<div class="card-body p-32 text-center text-secondary-light">
				You haven’t saved any addresses yet. Add one to speed up future orders.
			</div>
		</div>
	@else
		<div class="row g-4">
			@foreach($addresses as $address)
				<div class="col-xl-4 col-md-6">
					<div class="card border-0 shadow-sm h-100">
						<div class="card-body p-24 d-flex flex-column gap-16">
							<div class="d-flex justify-content-between align-items-start gap-2">
								<div>
									<div class="fw-semibold text-sm text-uppercase">{{ $address->label }}</div>
									<div class="text-xs text-secondary-light">{{ ucfirst($address->type) }} address</div>
								</div>
								@if($address->is_default)
									<span class="badge bg-primary-50 text-primary-600 radius-8 text-xxs fw-semibold">Default</span>
								@endif
							</div>
							<div class="text-sm text-secondary">
								@if($address->contact_name)
									<div class="fw-medium text-secondary">{{ $address->contact_name }}</div>
								@endif
								@if($address->contact_phone)
									<div>{{ $address->contact_phone }}</div>
								@endif
								<div class="mt-2">
									{{ $address->street }}<br>
									{{ $address->city }} @if($address->state), {{ $address->state }} @endif {{ $address->postal_code }}<br>
									{{ $address->country }}
								</div>
							</div>
							<div class="d-flex flex-wrap gap-2 mt-auto">
								@if(!$address->is_default)
									<form action="{{ route('customer.addresses.default', $address) }}" method="POST" class="d-inline">
										@csrf
										<button class="btn btn-sm btn-outline-secondary radius-12 px-16" type="submit">Make Default</button>
									</form>
								@endif
								<a href="{{ route('customer.addresses.edit', $address) }}" class="btn btn-sm btn-outline-secondary radius-12 px-16">Edit</a>
								<form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Remove this address?');">
									@csrf
									@method('DELETE')
									<button class="btn btn-sm btn-outline-danger radius-12 px-16" type="submit">Delete</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@endif
</div>
@endsection

