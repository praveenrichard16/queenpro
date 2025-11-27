@extends('layouts.customer')

@section('title', 'New Support Ticket')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Open a Support Ticket</h6>
			<p class="text-secondary-light mb-0">Share the details of your issue and our team will get back to you shortly.</p>
		</div>
		<a href="{{ route('customer.support.tickets.index') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
			Back to tickets
		</a>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			<form action="{{ route('customer.support.tickets.store') }}" method="POST" enctype="multipart/form-data" class="d-grid gap-4">
				@csrf
				<div class="row g-4">
					<div class="col-md-8">
						<label class="form-label text-secondary-light">Subject</label>
						<input type="text" name="subject" class="form-control bg-neutral-50 radius-12 @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Describe your request in a few words" required>
						@error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
					</div>
					<div class="col-md-4">
						<label class="form-label text-secondary-light">Priority</label>
						<select name="priority" class="form-select bg-neutral-50 radius-12">
							@foreach($priorities as $priority)
								<option value="{{ $priority->value }}" @selected(old('priority') === $priority->value)>{{ $priority->label() }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label text-secondary-light">Category</label>
						<select name="category_id" class="form-select bg-neutral-50 radius-12">
							<option value="">General Support</option>
							@foreach($categories as $category)
								<option value="{{ $category->id }}"
										data-default-priority="{{ $category->default_priority }}"
										@selected(old('category_id') == $category->id)>
									{{ $category->name }}
									@if($category->default_priority)
										— Default {{ ucfirst($category->default_priority) }}
									@endif
								</option>
							@endforeach
						</select>
					</div>
				</div>

				<div>
					<label class="form-label text-secondary-light">Describe your issue</label>
					<textarea name="message" rows="6" class="form-control bg-neutral-50 radius-12 @error('message') is-invalid @enderror" placeholder="Provide as many details as possible (order numbers, product names, etc.)" required>{{ old('message') }}</textarea>
					@error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>

				<div>
					<label class="form-label text-secondary-light">Attachments</label>
					<input type="file" name="attachments[]" class="form-control bg-neutral-50 radius-12 @error('attachments.*') is-invalid @enderror" multiple>
					<div class="form-text">You can upload up to 3 MB per file. Accepted formats: images, PDF, text.</div>
					@error('attachments.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>

				<div>
					<button class="btn btn-primary radius-12 px-24" type="submit">Submit ticket</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const categorySelect = document.querySelector('select[name="category_id"]');
		const prioritySelect = document.querySelector('select[name="priority"]');

		if (!categorySelect || !prioritySelect) {
			return;
		}

		const defaultPriority = prioritySelect.value;

		categorySelect.addEventListener('change', function () {
			const option = categorySelect.options[categorySelect.selectedIndex];
			const suggested = option?.dataset?.defaultPriority;

			if (!suggested) {
				prioritySelect.value = defaultPriority;
				return;
			}

			for (const opt of prioritySelect.options) {
				if (opt.value === suggested) {
					prioritySelect.value = suggested;
					break;
				}
			}
		});
	});
</script>
@endpush

