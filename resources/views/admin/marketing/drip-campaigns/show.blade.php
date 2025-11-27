@extends('layouts.admin')

@section('title', 'Drip Campaign Details')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $dripCampaign->name }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.marketing.drip-campaigns.index') }}" class="hover-text-primary">Drip Campaigns</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Details</li>
		</ul>
	</div>

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Campaign Steps</h6>
					@foreach($dripCampaign->steps as $step)
						<div class="border radius-12 p-3 mb-3">
							<div class="d-flex justify-content-between align-items-start">
								<div>
									<h6 class="fw-semibold mb-1">Step {{ $step->step_number }}</h6>
									<p class="text-sm text-secondary-light mb-1">
										Delay: {{ $step->delay_hours }} hours | 
										Channel: {{ ucfirst($step->channel) }} | 
										Template: {{ $step->template->name ?? 'N/A' }}
									</p>
								</div>
								<span class="badge bg-{{ $step->is_active ? 'success' : 'secondary' }}">
									{{ $step->is_active ? 'Active' : 'Inactive' }}
								</span>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Campaign Info</h6>
					<div class="mb-3">
						<label class="text-secondary-light small">Trigger Type</label>
						<div class="fw-medium">{{ ucfirst(str_replace('_', ' ', $dripCampaign->trigger_type)) }}</div>
					</div>
					<div class="mb-3">
						<label class="text-secondary-light small">Channel</label>
						<div class="fw-medium">{{ ucfirst($dripCampaign->channel) }}</div>
					</div>
					<div class="mb-3">
						<label class="text-secondary-light small">Status</label>
						<div>
							<span class="badge bg-{{ $dripCampaign->is_active ? 'success' : 'secondary' }}">
								{{ $dripCampaign->is_active ? 'Active' : 'Inactive' }}
							</span>
						</div>
					</div>
					<div class="mb-3">
						<label class="text-secondary-light small">Total Recipients</label>
						<div class="fw-medium">{{ $dripCampaign->recipients->count() }}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

