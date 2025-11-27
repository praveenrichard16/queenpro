@extends('layouts.admin')

@section('title', $template->id ? 'Edit Invoice Template' : 'Add Invoice Template')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $template->id ? 'Edit Invoice Template' : 'Add Invoice Template' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.invoices.templates.index') }}" class="hover-text-primary">Templates</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $template->id ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $template->exists ? route('admin.invoices.templates.update', $template) : route('admin.invoices.templates.store') }}" class="row g-4">
				@csrf
				@if($template->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $template->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Header HTML</label>
					<textarea name="header_html" rows="5" class="form-control bg-neutral-50 radius-12 @error('header_html') is-invalid @enderror" placeholder="HTML for invoice header">{{ old('header_html', $template->header_html) }}</textarea>
					@error('header_html') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Footer HTML</label>
					<textarea name="footer_html" rows="5" class="form-control bg-neutral-50 radius-12 @error('footer_html') is-invalid @enderror" placeholder="HTML for invoice footer">{{ old('footer_html', $template->footer_html) }}</textarea>
					@error('footer_html') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="is_default" name="is_default" @checked(old('is_default', $template->is_default))>
						<label class="form-check-label ms-8" for="is_default">
							Set as default template
						</label>
					</div>
				</div>

				<div class="col-12">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="is_active" name="is_active" @checked(old('is_active', $template->is_active ?? true))>
						<label class="form-check-label ms-8" for="is_active">
							Active
						</label>
					</div>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24">{{ $template->id ? 'Update Template' : 'Create Template' }}</button>
					<a href="{{ route('admin.invoices.templates.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

