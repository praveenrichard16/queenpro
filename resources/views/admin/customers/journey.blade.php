@extends('layouts.admin')

@section('title', 'Customer Journey')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $customer->name }} - Journey</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.customers.index') }}" class="hover-text-primary">Customers</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.customers.show', $customer) }}" class="hover-text-primary">{{ $customer->name }}</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Journey</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<h6 class="fw-semibold mb-16">Customer Journey Timeline</h6>
			@if($events->count() > 0)
				<div class="timeline">
					@foreach($events as $event)
						<div class="timeline-item mb-24 d-flex gap-3">
							<div class="timeline-marker">
								<div class="w-12-px h-12-px rounded-circle bg-primary-main"></div>
							</div>
							<div class="flex-grow-1">
								<div class="d-flex justify-content-between align-items-start mb-8">
									<div>
										<h6 class="text-sm fw-semibold mb-2">{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</h6>
										<span class="badge bg-info">{{ ucfirst($event->event_category) }}</span>
									</div>
									<span class="text-xs text-secondary-light">{{ $event->created_at->format('M d, Y h:i A') }}</span>
								</div>
								@if($event->event_data)
									<div class="bg-neutral-50 p-12 radius-12">
										<pre class="text-xs mb-0">{{ json_encode($event->event_data, JSON_PRETTY_PRINT) }}</pre>
									</div>
								@endif
							</div>
						</div>
					@endforeach
				</div>
			@else
				<p class="text-secondary-light mb-0">No journey events recorded yet.</p>
			@endif

			@if($events->hasPages())
				<div class="mt-24">
					{{ $events->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

