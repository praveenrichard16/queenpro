@extends('layouts.admin')

@section('title', 'View Enquiry')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">View Enquiry</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.enquiries.index') }}" class="hover-text-primary">Enquiries</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">View</li>
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
					<h6 class="fw-semibold mb-16">Enquiry Details</h6>
					
					<div class="mb-4">
						<label class="text-secondary-light small">Subject</label>
						<div class="fw-medium">{{ $enquiry->subject ?? 'No subject' }}</div>
					</div>

					@if($enquiry->message)
						<div class="mb-4">
							<label class="text-secondary-light small">Message</label>
							<div class="p-3 bg-neutral-50 rounded">{{ $enquiry->message }}</div>
						</div>
					@endif

					@if($enquiry->product)
						<div class="mb-4">
							<label class="text-secondary-light small">Product</label>
							<div>
								<a href="{{ route('products.show', $enquiry->product) }}" target="_blank" class="text-primary">{{ $enquiry->product->name }}</a>
							</div>
						</div>
					@endif

					<div class="mb-4">
						<label class="text-secondary-light small">Customer</label>
						<div class="fw-medium">{{ $enquiry->user->name ?? 'Guest' }}</div>
						@if($enquiry->user)
							<div class="text-sm text-secondary-light">{{ $enquiry->user->email }}</div>
							@if($enquiry->user->phone)
								<div class="text-sm text-secondary-light">{{ $enquiry->user->phone }}</div>
							@endif
						@endif
					</div>

					@if($enquiry->lead)
						<div class="mb-4">
							<label class="text-secondary-light small">Converted To Lead</label>
							<div>
								<a href="{{ route('admin.leads.edit', $enquiry->lead) }}" class="text-primary">{{ $enquiry->lead->name }}</a>
							</div>
						</div>
					@endif

					<div class="mb-4">
						<label class="text-secondary-light small">Created</label>
						<div>{{ $enquiry->created_at->format('d M Y H:i') }}</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Actions</h6>
					
					<form method="POST" action="{{ route('admin.enquiries.status', $enquiry) }}" class="mb-3">
						@csrf
						<label class="form-label text-secondary-light">Update Status</label>
						<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px mb-2" onchange="this.form.submit()">
							<option value="new" @selected($enquiry->status === 'new')>New</option>
							<option value="in_progress" @selected($enquiry->status === 'in_progress')>In Progress</option>
							<option value="converted" @selected($enquiry->status === 'converted') disabled>Converted</option>
							<option value="closed" @selected($enquiry->status === 'closed')>Closed</option>
						</select>
					</form>

					@if(!$enquiry->lead)
						<hr class="my-4">
						<h6 class="fw-semibold mb-16">Convert to Lead</h6>
						<form method="POST" action="{{ route('admin.enquiries.convert-to-lead', $enquiry) }}">
							@csrf
							<div class="mb-3">
								<label class="form-label text-secondary-light small">Lead Source</label>
								<select name="lead_source_id" class="form-select bg-neutral-50 radius-12 h-56-px">
									<option value="">Select Source</option>
									@foreach(\App\Models\LeadSource::where('is_active', true)->orderBy('name')->get() as $source)
										<option value="{{ $source->id }}">{{ $source->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label text-secondary-light small">Expected Value</label>
								<input type="number" name="expected_value" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px">
							</div>
							<div class="mb-3">
								<label class="form-label text-secondary-light small">Notes</label>
								<textarea name="notes" rows="3" class="form-control bg-neutral-50 radius-12"></textarea>
							</div>
							<button type="submit" class="btn btn-primary w-100 radius-12">Convert to Lead</button>
						</form>
					@else
						<div class="alert alert-info">
							This enquiry has already been converted to a lead.
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

