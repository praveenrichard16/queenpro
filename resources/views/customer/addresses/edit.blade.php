@extends('layouts.customer')

@section('title', 'Edit Address')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Edit address</h6>
			<p class="text-secondary-light mb-0">Update the details of this saved address.</p>
		</div>
		<a href="{{ route('customer.addresses.index') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
			Back to addresses
		</a>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			<form method="POST" action="{{ route('customer.addresses.update', $address) }}" class="d-grid gap-4">
				@csrf
				@method('PUT')
				@include('customer.addresses.form', ['address' => $address])
				<div>
					<button class="btn btn-primary radius-12 px-24" type="submit">Update address</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

