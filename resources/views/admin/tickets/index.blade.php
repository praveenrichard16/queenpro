@extends('layouts.admin')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Support Tickets')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-4">Support Tickets</h6>
			<p class="text-secondary-light mb-0">Monitor incoming support requests, track SLA status, and jump into conversations.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Support Tickets</li>
			<li>
				<a href="{{ route('admin.support.settings.edit') }}" class="btn btn-outline-secondary radius-12 px-18 d-flex align-items-center gap-2">
					<iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
					Settings
				</a>
			</li>
		</ul>
	</div>

	<div class="row g-3 mb-24">
		@php
			$statusColorMap = [
				'open' => 'bg-primary-50 text-primary-600',
				'in_progress' => 'bg-warning-50 text-warning',
				'awaiting_customer' => 'bg-info-50 text-info-main',
				'resolved' => 'bg-success-50 text-success',
				'closed' => 'bg-neutral-100 text-secondary-light',
			];
			$totalTickets = $statusCounts->sum();
		@endphp

		<div class="col-xl-2 col-sm-4 col-6">
			<div class="card border-0 h-100">
				<div class="card-body p-20">
					<span class="text-sm text-secondary-light d-block mb-2">Total</span>
					<h5 class="mb-0">{{ $totalTickets }}</h5>
				</div>
			</div>
		</div>

		@foreach($statuses as $status)
			@php
				$count = $statusCounts->get($status->value, 0);
				$badgeClass = $statusColorMap[$status->value] ?? 'bg-neutral-100 text-secondary-light';
			@endphp
			<div class="col-xl-2 col-sm-4 col-6">
				<div class="card border-0 h-100 {{ $count ? 'shadow-sm' : '' }}">
					<div class="card-body p-20">
						<span class="text-sm text-secondary-light d-flex align-items-center gap-2 mb-8">
							<span class="badge {{ $badgeClass }} px-10 py-6 radius-8 text-xs fw-semibold">{{ strtoupper(str_replace('_', ' ', $status->value)) }}</span>
						</span>
						<h5 class="mb-0">{{ $count }}</h5>
					</div>
				</div>
			</div>
		@endforeach
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="GET" action="{{ route('admin.support.tickets.index') }}" class="row g-3 align-items-end mb-20">
				<div class="col-xl-3 col-md-6">
					<label class="form-label text-secondary-light">Search</label>
					<input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control bg-neutral-50 radius-12" placeholder="Ticket number or subject">
				</div>
				<div class="col-xl-2 col-md-4">
					<label class="form-label text-secondary-light">Status</label>
					<select name="status" class="form-select bg-neutral-50 radius-12">
						<option value="">All statuses</option>
						@foreach($statuses as $status)
							<option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ $status->label() }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xl-2 col-md-4">
					<label class="form-label text-secondary-light">Priority</label>
					<select name="priority" class="form-select bg-neutral-50 radius-12">
						<option value="">All priorities</option>
						@foreach($priorities as $priority)
							<option value="{{ $priority->value }}" @selected(($filters['priority'] ?? '') === $priority->value)>{{ $priority->label() }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xl-3 col-md-6">
					<label class="form-label text-secondary-light">Assignee</label>
					<select name="assignee" class="form-select bg-neutral-50 radius-12">
						<option value="">Any</option>
						@foreach($assignees as $assignee)
							<option value="{{ $assignee->id }}" @selected(($filters['assignee'] ?? '') == $assignee->id)>{{ $assignee->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2 d-flex gap-2">
					<button class="btn btn-primary radius-12 flex-grow-1">Filter</button>
					@if(!empty($filters))
						<a href="{{ route('admin.support.tickets.index') }}" class="btn btn-outline-secondary radius-12">Reset</a>
					@endif
				</div>
			</form>

			<div class="table-responsive">
				<table class="table align-middle mb-0">
					<thead>
						<tr>
							<th class="text-secondary-light text-uppercase text-xs fw-semibold">Ticket</th>
							<th class="text-secondary-light text-uppercase text-xs fw-semibold">Subject</th>
							<th class="text-secondary-light text-uppercase text-xs fw-semibold">Customer</th>
							<th class="text-secondary-light text-uppercase text-xs fw-semibold">Priority</th>
							<th class="text-secondary-light text-uppercase text-xs fw-semibold">Status</th>
							<th class="text-secondary-light text-uppercase text-xs fw-semibold">Assignee</th>
							<th class="text-secondary-light text-uppercase text-xs fw-semibold text-end">Updated</th>
						</tr>
					</thead>
					<tbody>
					@forelse($tickets as $ticket)
						<tr>
							<td class="fw-semibold">
								<a href="{{ route('admin.support.tickets.show', $ticket) }}" class="text-decoration-none hover-text-primary">{{ $ticket->ticket_number }}</a>
								<div class="text-xs text-secondary-light">{{ optional($ticket->category)->name ?? 'General' }}</div>
							</td>
							<td>
								<div class="fw-medium text-sm">{{ Str::limit($ticket->subject, 64) }}</div>
								@if($ticket->meta && !empty($ticket->meta['channel']))
									<span class="badge bg-neutral-100 text-secondary-light radius-8 text-xs mt-1">{{ ucfirst($ticket->meta['channel']) }}</span>
								@endif
							</td>
							<td>
								@if($ticket->customer)
									<div class="fw-medium text-sm">{{ $ticket->customer->name }}</div>
									<div class="text-xs text-secondary-light">{{ $ticket->customer->email }}</div>
								@else
									<span class="badge bg-neutral-100 text-secondary-light radius-8 text-xs">Guest</span>
								@endif
							</td>
							<td>
								<span class="badge radius-8 text-xs fw-semibold
									@if($ticket->priority->value === 'urgent') bg-danger-50 text-danger
									@elseif($ticket->priority->value === 'high') bg-warning-50 text-warning
									@elseif($ticket->priority->value === 'medium') bg-info-50 text-info-main
									@else bg-neutral-100 text-secondary-light @endif">
									{{ $ticket->priority->label() }}
								</span>
							</td>
							<td>
								<span class="badge radius-8 text-xs fw-semibold {{ $statusColorMap[$ticket->status->value] ?? 'bg-neutral-100 text-secondary-light' }}">
									{{ $ticket->status->label() }}
								</span>
							</td>
							<td>
								@if($ticket->assignee)
									<div class="fw-medium text-sm">{{ $ticket->assignee->name }}</div>
								@else
									<span class="text-secondary-light text-sm">Unassigned</span>
								@endif
							</td>
							<td class="text-end text-sm text-secondary-light">
								{{ $ticket->updated_at?->diffForHumans() }}
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="7" class="text-center py-24 text-secondary-light">
								No tickets found for the current filters.
							</td>
						</tr>
					@endforelse
					</tbody>
				</table>
			</div>

			<div class="mt-24">
				{{ $tickets->links() }}
			</div>
		</div>
	</div>
</div>
@endsection

