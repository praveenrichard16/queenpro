@extends('layouts.admin')

@section('title', 'Trigger Drip Campaign')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Trigger Drip Campaign</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.marketing.drip-campaigns.index') }}" class="hover-text-primary">Drip Campaigns</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Trigger</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="row">
		<div class="col-lg-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Select Campaign</h6>
					<form method="GET" id="campaignForm">
						<input type="hidden" name="recipient_type" value="{{ $recipientType ?? 'customer' }}">
						<input type="hidden" name="search" value="{{ request('search') }}">
						<select name="campaign_id" class="form-select bg-neutral-50 radius-12" onchange="document.getElementById('campaignForm').submit()">
							<option value="">Select Campaign</option>
							@foreach($campaigns as $campaign)
								<option value="{{ $campaign->id }}" @selected(request('campaign_id') == $campaign->id)>
									{{ $campaign->name }}
								</option>
							@endforeach
						</select>
					</form>

					@if($selectedCampaign)
						<hr>
						<p class="text-secondary mb-1">
							<strong>Timezone:</strong> {{ $selectedCampaign->timezone ?? config('app.timezone') }}
						</p>
						<p class="text-secondary mb-1">
							<strong>Send Window:</strong>
							{{ $selectedCampaign->send_window_start ? \Carbon\Carbon::parse($selectedCampaign->send_window_start)->format('H:i') : 'Anytime' }}
							–
							{{ $selectedCampaign->send_window_end ? \Carbon\Carbon::parse($selectedCampaign->send_window_end)->format('H:i') : 'Anytime' }}
						</p>
						<p class="text-secondary mb-1">
							<strong>Channel:</strong> {{ ucfirst($selectedCampaign->channel) }}
						</p>
						<p class="text-secondary mb-3">
							<strong>Max Retries:</strong> {{ $selectedCampaign->max_retries ?? 3 }}
						</p>
						@if(!empty($selectedCampaign->audience_filters))
							<div class="mb-3">
								<strong class="d-block text-secondary-light mb-1">Audience Filters</strong>
								@foreach($selectedCampaign->audience_filters as $filter)
									<span class="badge bg-neutral-100 text-dark me-1 mb-1 text-capitalize">{{ str_replace('_', ' ', $filter) }}</span>
								@endforeach
							</div>
						@endif
						@if($selectedCampaign->steps->count())
							<div>
								<strong class="d-block text-secondary-light mb-1">Steps</strong>
								<ol class="ps-3">
									@foreach($selectedCampaign->steps as $step)
										<li class="mb-1">
											<span class="fw-semibold">Step {{ $step->step_number }}</span> •
											{{ ucfirst($step->channel) }} •
											Delay: {{ $step->delay_hours }}h
											@if($step->wait_until_event)
												<span class="badge bg-info ms-1">{{ str_replace('_', ' ', $step->wait_until_event) }}</span>
											@endif
										</li>
									@endforeach
								</ol>
							</div>
						@endif
					@endif
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					@if(request('campaign_id'))
						<!-- Recipient Type Tabs -->
						<ul class="nav nav-pills mb-3" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link {{ ($recipientType ?? 'customer') === 'customer' ? 'active' : '' }}" 
										type="button" 
										onclick="window.location.href='{{ route('admin.marketing.drip-campaigns.trigger', ['campaign_id' => request('campaign_id'), 'recipient_type' => 'customer']) }}'">
									Customers
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link {{ ($recipientType ?? '') === 'enquiry' ? 'active' : '' }}" 
										type="button"
										onclick="window.location.href='{{ route('admin.marketing.drip-campaigns.trigger', ['campaign_id' => request('campaign_id'), 'recipient_type' => 'enquiry']) }}'">
									Enquiries
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link {{ ($recipientType ?? '') === 'lead' ? 'active' : '' }}" 
										type="button"
										onclick="window.location.href='{{ route('admin.marketing.drip-campaigns.trigger', ['campaign_id' => request('campaign_id'), 'recipient_type' => 'lead']) }}'">
									Leads
								</button>
							</li>
						</ul>

						<form method="GET" class="mb-3">
							<input type="hidden" name="campaign_id" value="{{ request('campaign_id') }}">
							<input type="hidden" name="recipient_type" value="{{ $recipientType ?? 'customer' }}">
							<div class="icon-field mb-3">
								<span class="icon top-50 translate-middle-y">
									<iconify-icon icon="mage:search"></iconify-icon>
								</span>
								<input type="text" name="search" value="{{ request('search') }}" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Search {{ $recipientType ?? 'customers' }}...">
							</div>

							@if(($recipientType ?? 'customer') === 'customer')
								<div class="row g-3">
									<div class="col-md-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="has_orders" value="1" id="filter-orders" {{ request()->boolean('has_orders') ? 'checked' : '' }}>
											<label class="form-check-label" for="filter-orders">Only customers with orders</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="has_abandoned_cart" value="1" id="filter-abandoned" {{ request()->boolean('has_abandoned_cart') ? 'checked' : '' }}>
											<label class="form-check-label" for="filter-abandoned">Has abandoned cart</label>
										</div>
									</div>
								</div>
							@elseif(($recipientType ?? '') === 'lead')
								<div class="row g-3">
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Lead Stage</label>
										<select name="lead_stage_id" class="form-select bg-neutral-50 radius-12">
											<option value="">All stages</option>
											@foreach($leadStages as $stage)
												<option value="{{ $stage->id }}" @selected(request('lead_stage_id') == $stage->id)>
													{{ $stage->name }}
												</option>
											@endforeach
										</select>
									</div>
									<div class="col-md-3">
										<label class="form-label text-secondary-light">Min Score</label>
										<input type="number" name="min_score" value="{{ request('min_score') }}" class="form-control bg-neutral-50 radius-12" min="0" max="100">
									</div>
									<div class="col-md-3 d-flex align-items-end">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="only_hot" value="1" id="filter-hot" {{ request()->boolean('only_hot') ? 'checked' : '' }}>
											<label class="form-check-label" for="filter-hot">Hot leads only</label>
										</div>
									</div>
								</div>
							@elseif(($recipientType ?? '') === 'enquiry')
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="recent_only" value="1" id="filter-recent" {{ request()->boolean('recent_only') ? 'checked' : '' }}>
									<label class="form-check-label" for="filter-recent">Show last 30 days only</label>
								</div>
							@endif

							<div class="mt-3 d-flex gap-2">
								<button type="submit" class="btn btn-outline-primary">Apply Filters</button>
								<a href="{{ route('admin.marketing.drip-campaigns.trigger', ['campaign_id' => request('campaign_id'), 'recipient_type' => $recipientType ?? 'customer']) }}" class="btn btn-outline-secondary">Reset</a>
							</div>
						</form>

						<form method="POST" action="{{ route('admin.marketing.drip-campaigns.trigger-campaign') }}" id="triggerForm">
							@csrf
							<input type="hidden" name="campaign_id" value="{{ request('campaign_id') }}">
							<input type="hidden" name="recipient_type" value="{{ $recipientType ?? 'customer' }}">

							@if(($recipientType ?? 'customer') === 'customer')
								<div class="table-responsive">
									<table class="table bordered-table mb-0">
										<thead>
											<tr>
												<th width="50">
													<input type="checkbox" id="selectAll" class="form-check-input">
												</th>
												<th>Customer</th>
												<th>Email</th>
												<th>Phone</th>
											</tr>
										</thead>
										<tbody>
											@forelse($customers as $customer)
												<tr>
													<td>
														<input type="checkbox" name="recipient_ids[]" value="{{ $customer->id }}" class="form-check-input recipient-checkbox">
													</td>
													<td>{{ $customer->name }}</td>
													<td>{{ $customer->email }}</td>
													<td>{{ $customer->phone ?? '-' }}</td>
												</tr>
											@empty
												<tr>
													<td colspan="4" class="text-center py-4 text-secondary-light">No customers found.</td>
												</tr>
											@endforelse
										</tbody>
									</table>
								</div>
								@if($customers->hasPages())
									<div class="mt-3">
										{{ $customers->links() }}
									</div>
								@endif
							@elseif(($recipientType ?? '') === 'enquiry')
								<div class="table-responsive">
									<table class="table bordered-table mb-0">
										<thead>
											<tr>
												<th width="50">
													<input type="checkbox" id="selectAll" class="form-check-input">
												</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Product</th>
											</tr>
										</thead>
										<tbody>
											@forelse($enquiries as $enquiry)
												<tr>
													<td>
														<input type="checkbox" name="recipient_ids[]" value="{{ $enquiry->id }}" class="form-check-input recipient-checkbox">
													</td>
													<td>{{ $enquiry->customer_name }}</td>
													<td>{{ $enquiry->customer_email }}</td>
													<td>{{ $enquiry->customer_phone ?? '-' }}</td>
													<td>{{ $enquiry->product->name ?? '-' }}</td>
												</tr>
											@empty
												<tr>
													<td colspan="5" class="text-center py-4 text-secondary-light">No enquiries found.</td>
												</tr>
											@endforelse
										</tbody>
									</table>
								</div>
								@if($enquiries->hasPages())
									<div class="mt-3">
										{{ $enquiries->links() }}
									</div>
								@endif
							@elseif(($recipientType ?? '') === 'lead')
								<div class="table-responsive">
									<table class="table bordered-table mb-0">
										<thead>
											<tr>
												<th width="50">
													<input type="checkbox" id="selectAll" class="form-check-input">
												</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											@forelse($leads as $lead)
												<tr>
													<td>
														<input type="checkbox" name="recipient_ids[]" value="{{ $lead->id }}" class="form-check-input recipient-checkbox">
													</td>
													<td>{{ $lead->name }}</td>
													<td>{{ $lead->email }}</td>
													<td>{{ $lead->phone ?? '-' }}</td>
													<td>
														<span class="badge bg-info">{{ $lead->stage->name ?? '-' }}</span>
													</td>
												</tr>
											@empty
												<tr>
													<td colspan="5" class="text-center py-4 text-secondary-light">No leads found.</td>
												</tr>
											@endforelse
										</tbody>
									</table>
								</div>
								@if($leads->hasPages())
									<div class="mt-3">
										{{ $leads->links() }}
									</div>
								@endif
							@endif

							<div class="mt-3 d-flex align-items-center justify-content-between">
								<div>
									<strong>Selected: <span id="selectedCount">0</span></strong>
								</div>
								<button type="submit" class="btn btn-primary">
									Trigger Campaign for Selected {{ ucfirst($recipientType ?? 'customers') }}
								</button>
							</div>
						</form>
					@else
						<p class="text-center py-4 text-secondary-light">Please select a campaign to trigger.</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script>
	document.getElementById('selectAll')?.addEventListener('change', function() {
		const checkboxes = document.querySelectorAll('.recipient-checkbox');
		checkboxes.forEach(checkbox => {
			checkbox.checked = this.checked;
		});
		updateSelectedCount();
	});

	document.querySelectorAll('.recipient-checkbox').forEach(checkbox => {
		checkbox.addEventListener('change', function() {
			const allCheckboxes = document.querySelectorAll('.recipient-checkbox');
			const checkedCheckboxes = document.querySelectorAll('.recipient-checkbox:checked');
			const selectAll = document.getElementById('selectAll');
			
			if (selectAll) {
				selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
			}
			updateSelectedCount();
		});
	});

	function updateSelectedCount() {
		const checked = document.querySelectorAll('.recipient-checkbox:checked');
		const countElement = document.getElementById('selectedCount');
		if (countElement) {
			countElement.textContent = checked.length;
		}
	}

	document.getElementById('triggerForm')?.addEventListener('submit', function(e) {
		const checked = document.querySelectorAll('.recipient-checkbox:checked');
		if (checked.length === 0) {
			e.preventDefault();
			alert('Please select at least one recipient.');
			return false;
		}
	});

	// Initialize count on page load
	updateSelectedCount();
</script>
@endpush
@endsection

