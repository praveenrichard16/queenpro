@extends('layouts.customer')

@section('title', 'Support Tickets')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Support Tickets</h6>
			<p class="text-secondary-light mb-0">Keep track of all your conversations with our support specialists.</p>
		</div>
		<a href="{{ route('customer.support.tickets.create') }}" class="btn btn-primary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:plus-circle-line-duotone" class="text-lg"></iconify-icon>
			New Ticket
		</a>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			@if($tickets->isEmpty())
				<div class="text-center py-40 text-secondary-light">
					No tickets found. <a href="{{ route('customer.support.tickets.create') }}" class="text-primary-light fw-semibold">Open your first ticket</a>.
				</div>
			@else
				<div class="table-responsive">
					<table class="table align-middle mb-0">
						<thead>
							<tr>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Ticket</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Subject</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Status</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold">Updated</th>
								<th class="text-secondary-light text-xs text-uppercase fw-semibold text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($tickets as $ticket)
							<tr>
								<td class="fw-semibold text-sm">{{ $ticket->ticket_number }}</td>
								<td class="text-sm text-secondary-light">{{ $ticket->subject }}</td>
								<td>
									<span class="badge radius-8 text-xs fw-semibold
										@if($ticket->status->value === 'open') bg-warning-50 text-warning
										@elseif($ticket->status->value === 'in_progress') bg-info-50 text-info-main
										@elseif($ticket->status->value === 'awaiting_customer') bg-primary-50 text-primary-600
										@elseif($ticket->status->value === 'resolved') bg-success-50 text-success
										@else bg-neutral-100 text-secondary-light @endif">
										{{ $ticket->status->label() }}
									</span>
								</td>
								<td class="text-sm text-secondary-light">{{ $ticket->updated_at?->diffForHumans() }}</td>
								<td class="text-end">
									<a href="{{ route('customer.support.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-secondary radius-12 px-16">View</a>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="mt-24">
					{{ $tickets->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

