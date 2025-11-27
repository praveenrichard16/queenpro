@extends('layouts.admin')

@section('title', 'Campaign Details')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $campaign->name }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.marketing.campaigns.index') }}" class="hover-text-primary">Campaigns</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Details</li>
		</ul>
	</div>

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Campaign Information</h6>
					<div class="row g-3">
						<div class="col-6">
							<span class="text-secondary-light">Status:</span>
							<span class="badge bg-{{ $campaign->status === 'completed' ? 'success' : 'warning' }} ms-2">{{ ucfirst($campaign->status) }}</span>
						</div>
						<div class="col-6">
							<span class="text-secondary-light">Template:</span>
							<span class="fw-semibold ms-2">{{ $campaign->template->name }}</span>
						</div>
						<div class="col-6">
							<span class="text-secondary-light">Total Recipients:</span>
							<span class="fw-semibold ms-2">{{ $campaign->total_recipients }}</span>
						</div>
						<div class="col-6">
							<span class="text-secondary-light">Sent:</span>
							<span class="fw-semibold ms-2">{{ $campaign->sent_count }}/{{ $campaign->total_recipients }}</span>
						</div>
						@if($campaign->scheduled_at)
							<div class="col-6">
								<span class="text-secondary-light">Scheduled:</span>
								<span class="fw-semibold ms-2">{{ $campaign->scheduled_at->format('M d, Y h:i A') }}</span>
							</div>
						@endif
						@if($campaign->sent_at)
							<div class="col-6">
								<span class="text-secondary-light">Sent At:</span>
								<span class="fw-semibold ms-2">{{ $campaign->sent_at->format('M d, Y h:i A') }}</span>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Statistics</h6>
					<div class="d-flex flex-column gap-3">
						<div class="d-flex justify-content-between">
							<span class="text-secondary-light">Delivered</span>
							<span class="fw-semibold">{{ $campaign->delivered_count }}</span>
						</div>
						<div class="d-flex justify-content-between">
							<span class="text-secondary-light">Opened</span>
							<span class="fw-semibold">{{ $campaign->opened_count }}</span>
						</div>
						<div class="d-flex justify-content-between">
							<span class="text-secondary-light">Clicked</span>
							<span class="fw-semibold">{{ $campaign->clicked_count }}</span>
						</div>
					</div>
					@if($campaign->status === 'draft' || $campaign->status === 'scheduled')
						<form action="{{ route('admin.marketing.campaigns.send', $campaign) }}" method="POST" class="mt-16">
							@csrf
							<button type="submit" class="btn btn-success w-100 radius-12" onclick="return confirm('Send this campaign?')">
								Send Campaign
							</button>
						</form>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

