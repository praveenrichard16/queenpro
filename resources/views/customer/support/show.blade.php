@extends('layouts.customer')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Ticket '.$ticket->ticket_number)

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Ticket {{ $ticket->ticket_number }}</h6>
			<p class="text-secondary-light mb-0">{{ $ticket->subject }}</p>
		</div>
		<a href="{{ route('customer.support.tickets.index') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
			Back to tickets
		</a>
	</div>

	<div class="card border-0 shadow-sm mb-24">
		<div class="card-body p-24">
			<div class="mb-20 d-flex align-items-center gap-3">
				<span class="badge radius-8 text-xs fw-semibold
					@if($ticket->status->value === 'open') bg-warning-50 text-warning
					@elseif($ticket->status->value === 'in_progress') bg-info-50 text-info-main
					@elseif($ticket->status->value === 'awaiting_customer') bg-primary-50 text-primary-600
					@elseif($ticket->status->value === 'resolved') bg-success-50 text-success
					@else bg-neutral-100 text-secondary-light @endif">
					{{ $ticket->status->label() }}
				</span>
				<span class="text-xs text-secondary-light">Updated {{ $ticket->updated_at?->diffForHumans() }}</span>
			</div>

			@if($ticket->messages->isEmpty())
				<p class="text-secondary-light">Conversation history isn’t available yet.</p>
			@else
				<ul class="list-unstyled d-grid gap-16 mb-0">
					@foreach($ticket->messages as $message)
						<li class="border radius-16 p-16 {{ $message->is_internal ? 'bg-neutral-100' : 'bg-neutral-50' }}">
							<div class="d-flex justify-content-between align-items-center mb-8">
								<div class="fw-semibold text-sm">
									{{ $message->author?->name ?? 'Support Team' }}
									@if($message->is_internal)
										<span class="badge bg-neutral-200 text-secondary-light radius-8 text-xxs ms-2">Internal note</span>
									@endif
								</div>
								<span class="text-xs text-secondary-light">{{ $message->created_at?->format('d M Y, H:i') }}</span>
							</div>
							<div class="text-sm text-secondary">
								{!! nl2br(e($message->body)) !!}
							</div>
							@if($message->attachments->isNotEmpty())
								<div class="mt-12 d-flex flex-wrap gap-2">
									@foreach($message->attachments as $attachment)
										<a href="{{ Storage::disk('public')->url($attachment->path) }}" class="btn btn-xs btn-outline-secondary radius-12 px-12 d-flex align-items-center gap-1" target="_blank" rel="noopener">
											<iconify-icon icon="solar:paperclip-linear" class="text-sm"></iconify-icon>
											<span class="text-xs">{{ $attachment->original_name }}</span>
										</a>
									@endforeach
								</div>
							@endif
						</li>
					@endforeach
				</ul>
			@endif
		</div>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			<h6 class="fw-semibold mb-16">Reply to support</h6>
			<form action="{{ route('customer.support.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="d-grid gap-3">
				@csrf
				<div>
					<textarea name="message" rows="4" class="form-control bg-neutral-50 radius-12 @error('message') is-invalid @enderror" placeholder="Write your reply here..." required>{{ old('message') }}</textarea>
					@error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div>
					<input type="file" name="attachments[]" class="form-control bg-neutral-50 radius-12 @error('attachments.*') is-invalid @enderror" multiple>
					<div class="form-text">Attach files up to 3 MB each to help us troubleshoot.</div>
					@error('attachments.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div>
					<button class="btn btn-primary radius-12 px-24" type="submit">Send reply</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

