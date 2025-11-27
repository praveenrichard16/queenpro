@extends('layouts.admin')

@section('title', 'Drip Campaigns')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Drip Campaigns</h6>
			<p class="text-secondary-light mb-0">Automated email and WhatsApp campaigns with multiple steps.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.marketing.campaigns.index') }}" class="hover-text-primary">Marketing</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Drip Campaigns</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<div class="d-flex justify-content-between align-items-center">
				<div class="d-flex gap-2">
					<a href="{{ route('admin.marketing.drip-campaigns.create') }}" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Create Drip Campaign
					</a>
					<a href="{{ route('admin.marketing.drip-campaigns.trigger') }}" class="btn btn-info radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:play-outline"></iconify-icon>
						Trigger Campaign
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Name</th>
							<th>Trigger</th>
							<th>Channel</th>
							<th>Delivery Window</th>
							<th>Audience</th>
							<th>Steps</th>
							<th>Recipients</th>
							<th>Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($campaigns as $campaign)
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium">{{ $campaign->name }}</h6>
									@if($campaign->description)
										<p class="text-sm text-secondary-light mb-0">{{ Str::limit($campaign->description, 50) }}</p>
									@endif
								</td>
								<td>
									<span class="badge bg-info">
										{{ ucfirst(str_replace('_', ' ', $campaign->trigger_type)) }}
									</span>
								</td>
								<td>
									<span class="badge bg-primary">{{ ucfirst($campaign->channel) }}</span>
								</td>
								<td>
									<span class="badge bg-info">
										{{ ucfirst(str_replace('_', ' ', $campaign->trigger_type)) }}
									</span>
								</td>
								<td>
									<span class="badge bg-primary">{{ ucfirst($campaign->channel) }}</span>
								</td>
								<td>
									<div class="text-secondary-light small">
										<div>{{ $campaign->timezone ?? config('app.timezone') }}</div>
										<div>
											{{ $campaign->send_window_start ? \Carbon\Carbon::parse($campaign->send_window_start)->format('H:i') : 'Anytime' }}
											–
											{{ $campaign->send_window_end ? \Carbon\Carbon::parse($campaign->send_window_end)->format('H:i') : 'Anytime' }}
										</div>
									</div>
								</td>
								<td>
									@if(!empty($campaign->audience_filters))
										@foreach($campaign->audience_filters as $filter)
											<span class="badge bg-neutral-100 text-dark text-capitalize me-1">{{ str_replace('_', ' ', $filter) }}</span>
										@endforeach
									@else
										<span class="text-secondary-light">All recipients</span>
									@endif
								</td>
								<td>
									<div class="d-flex flex-column">
										<span class="badge bg-secondary mb-1">{{ $campaign->steps_count }} step(s)</span>
										<small class="text-secondary-light">Retries: {{ $campaign->max_retries ?? 3 }}</small>
									</div>
								</td>
								<td>
									<span class="badge bg-info">{{ $campaign->recipients_count }}</span>
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $campaign->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
										{{ $campaign->is_active ? 'Active' : 'Inactive' }}
									</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										@if($campaign->trigger_type === 'manual')
											<a href="{{ route('admin.marketing.drip-campaigns.trigger', ['campaign_id' => $campaign->id]) }}" class="w-36-px h-36-px bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center" title="Trigger">
												<iconify-icon icon="solar:play-outline"></iconify-icon>
											</a>
										@endif
										<a href="{{ route('admin.marketing.drip-campaigns.show', $campaign) }}" class="w-36-px h-36-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center" title="View">
											<iconify-icon icon="solar:eye-outline"></iconify-icon>
										</a>
										<a href="{{ route('admin.marketing.drip-campaigns.edit', $campaign) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Edit">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.marketing.drip-campaigns.destroy', $campaign) }}" method="POST" onsubmit="return confirm('Delete this drip campaign?')" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Delete">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No campaigns" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No drip campaigns found</h6>
									<p class="text-secondary-light mb-0">Create your first drip campaign to get started.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($campaigns->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $campaigns->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

