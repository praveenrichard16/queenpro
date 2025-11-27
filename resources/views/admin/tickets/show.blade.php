@extends('layouts.admin')

@php
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
@endphp

@section('title', 'Ticket '.$ticket->ticket_number)

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-2">Ticket {{ $ticket->ticket_number }}</h6>
			<p class="text-secondary-light mb-0">Conversation history, SLA milestones, and ticket context.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.support.tickets.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:arrow-left-linear" class="icon text-lg"></iconify-icon>
					Back to Tickets
				</a>
			</li>
		</ul>
	</div>

	<div class="row g-4">
		<div class="col-xxl-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-16">
						<div>
							<h5 class="mb-2">{{ $ticket->subject }}</h5>
							<div class="d-flex flex-wrap gap-2 align-items-center">
								<span class="badge radius-8 text-xs fw-semibold
									@if($ticket->priority->value === 'urgent') bg-danger-50 text-danger
									@elseif($ticket->priority->value === 'high') bg-warning-50 text-warning
									@elseif($ticket->priority->value === 'medium') bg-info-50 text-info-main
									@else bg-neutral-100 text-secondary-light @endif">
									{{ $ticket->priority->label() }}
								</span>
								<span class="badge radius-8 text-xs fw-semibold bg-neutral-100 text-secondary-light">
									{{ $ticket->status->label() }}
								</span>
								@if($ticket->category)
									<span class="text-secondary-light text-sm d-inline-flex align-items-center gap-1">
										<iconify-icon icon="solar:folder-outline" class="text-md"></iconify-icon>
										{{ $ticket->category->name }}
									</span>
								@endif
							</div>
						</div>
						<div class="text-end">
							<div class="text-secondary-light text-sm">Last updated</div>
							<div class="fw-semibold text-sm">{{ $ticket->updated_at?->diffForHumans() }}</div>
						</div>
					</div>

					@if($ticket->description)
						<div class="bg-neutral-50 radius-12 p-16 mb-24">
							<div class="text-sm text-secondary">
								{!! nl2br(e($ticket->description)) !!}
							</div>
						</div>
					@endif

					<h6 class="fw-semibold mb-16">Conversation</h6>
					<div class="timeline">
						@forelse($ticket->messages as $message)
							<div class="timeline-item d-flex gap-3 mb-24">
								<div class="timeline-marker mt-2">
									<span class="w-12 h-12 bg-primary-200 rounded-circle d-block"></span>
								</div>
								<div class="flex-grow-1">
									<div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
										<div>
											<div class="fw-semibold text-sm">
												@if($message->author)
													{{ $message->author->name }}
												@else
													System
												@endif
												@if($message->is_internal)
													<span class="badge bg-neutral-100 text-secondary-light radius-8 text-xxs ms-2">Internal note</span>
												@endif
											</div>
											@if($message->author && $message->author->email)
												<div class="text-xs text-secondary-light">{{ $message->author->email }}</div>
											@endif
										</div>
										<div class="text-xs text-secondary-light">
											{{ $message->created_at?->format('d M Y, H:i') }}
										</div>
									</div>
									@if($message->body)
										<div class="mt-12 text-sm text-secondary">
											{!! nl2br(e($message->body)) !!}
										</div>
									@endif

									@if($message->attachments->isNotEmpty())
										<div class="mt-12">
											<span class="text-xs text-secondary-light d-block mb-8">Attachments</span>
											<div class="d-flex flex-wrap gap-2">
												@foreach($message->attachments as $attachment)
													<a href="{{ Storage::disk('public')->exists($attachment->path) ? Storage::url($attachment->path) : '#' }}"
													   class="btn btn-outline-secondary radius-12 px-16 py-6 text-xs d-flex align-items-center gap-2"
													   @if(!Storage::disk('public')->exists($attachment->path)) aria-disabled="true" tabindex="-1" @endif>
														<iconify-icon icon="solar:paperclip-linear"></iconify-icon>
														{{ Str::limit($attachment->original_name, 32) }}
														@if($attachment->size)
															<span class="text-secondary-light">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
														@endif
													</a>
												@endforeach
											</div>
										</div>
									@endif
								</div>
							</div>
						@empty
							<div class="text-center text-secondary-light py-24">
								No messages recorded yet.
							</div>
						@endforelse
					</div>
				</div>
			</div>
		</div>

		<div class="col-xxl-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Ticket Overview</h6>
					<ul class="list-unstyled d-grid gap-12">
						<li class="d-flex justify-content-between">
							<span class="text-secondary-light text-sm">Customer</span>
							<div class="text-sm fw-semibold">
								@if($ticket->customer)
									<div>{{ $ticket->customer->name }}</div>
									<div class="text-secondary-light">{{ $ticket->customer->email }}</div>
								@else
									<span class="text-secondary-light">Guest</span>
								@endif
							</div>
						</li>
						<li class="d-flex justify-content-between">
							<span class="text-secondary-light text-sm">Assignee</span>
							<div class="text-sm fw-semibold">
								{{ $ticket->assignee->name ?? 'Unassigned' }}
							</div>
						</li>
						<li class="d-flex justify-content-between">
							<span class="text-secondary-light text-sm">Created</span>
							<div class="text-sm fw-semibold">
								{{ $ticket->created_at?->format('d M Y, H:i') }}
							</div>
						</li>
						<li class="d-flex justify-content-between">
							<span class="text-secondary-light text-sm">Last customer reply</span>
							<div class="text-sm fw-semibold">
								{{ $ticket->last_customer_reply_at?->diffForHumans() ?? '—' }}
							</div>
						</li>
						<li class="d-flex justify-content-between">
							<span class="text-secondary-light text-sm">Last staff reply</span>
							<div class="text-sm fw-semibold">
								{{ $ticket->last_staff_reply_at?->diffForHumans() ?? '—' }}
							</div>
						</li>
					</ul>
				</div>
			</div>

			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">SLA Targets</h6>
					@if($ticket->sla)
						<ul class="list-unstyled d-grid gap-12">
							<li class="d-flex justify-content-between">
								<span class="text-secondary-light text-sm">Policy name</span>
								<span class="fw-semibold text-sm">{{ $ticket->sla->name }}</span>
							</li>
							<li class="d-flex justify-content-between">
								<span class="text-secondary-light text-sm">Response target</span>
								<span class="fw-semibold text-sm">{{ $ticket->sla->response_minutes }} min</span>
							</li>
							<li class="d-flex justify-content-between">
								<span class="text-secondary-light text-sm">Resolution target</span>
								<span class="fw-semibold text-sm">{{ $ticket->sla->resolution_minutes }} min</span>
							</li>
							<li class="d-flex justify-content-between">
								<span class="text-secondary-light text-sm">Due by</span>
								<span class="fw-semibold text-sm">{{ $ticket->due_at?->format('d M Y, H:i') ?? '—' }}</span>
							</li>
						</ul>
					@else
						<p class="text-secondary-light text-sm mb-0">No SLA policy attached.</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card border-0 shadow-sm">
	<div class="card-body p-24">
		<h6 class="fw-semibold mb-16">Reply to customer</h6>
		<form action="{{ route('admin.support.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="d-grid gap-3">
			@csrf
			<div>
				<label class="form-label text-secondary-light">Message</label>
				<textarea name="message" rows="4" class="form-control bg-neutral-50 radius-12 @error('message') is-invalid @enderror" placeholder="Write your reply…" required>{{ old('message') }}</textarea>
				@error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
			</div>
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label text-secondary-light">Status</label>
					<select name="status" class="form-select bg-neutral-50 radius-12">
						@foreach($statuses as $status)
							<option value="{{ $status->value }}" @selected(old('status', $ticket->status->value) === $status->value)>{{ $status->label() }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label text-secondary-light">Priority</label>
					<select name="priority" class="form-select bg-neutral-50 radius-12">
						@foreach($priorities as $priority)
							<option value="{{ $priority->value }}" @selected(old('priority', $ticket->priority->value) === $priority->value)>{{ $priority->label() }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div>
				<label class="form-label text-secondary-light">Attachments</label>
				<input type="file" name="attachments[]" class="form-control bg-neutral-50 radius-12 @error('attachments.*') is-invalid @enderror" multiple>
				<div class="form-text">Up to 3 MB per file. Attachments are visible to the customer unless marked as internal.</div>
				@error('attachments.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
			</div>
			<div class="form-check form-switch">
				<input class="form-check-input" type="checkbox" id="admin-internal-note" name="is_internal" value="1" {{ old('is_internal') ? 'checked' : '' }}>
				<label class="form-check-label text-secondary-light" for="admin-internal-note">Mark as internal note (customer won’t be notified)</label>
			</div>
			<div>
				<button class="btn btn-primary radius-12 px-24" type="submit">Send reply</button>
			</div>
		</form>
	</div>
</div>
@endsection

