@extends('layouts.admin')

@section('title', $template->id ? 'Edit Template' : 'Add Template')

@php
	$currentType = old('type', $template->type ?? request('type', 'email'));
	$languageOptions = [
		'en' => 'English',
		'es' => 'Spanish',
		'ar' => 'Arabic',
		'hi' => 'Hindi',
		'fr' => 'French',
	];
	$categoryOptions = [
		'UTILITY' => 'Utility',
		'MARKETING' => 'Marketing',
		'AUTHENTICATION' => 'Authentication',
	];
	$meta = $template->meta ?? [];
	$variableTokens = old('variable_tokens', implode(', ', $template->variables ?? []));
@endphp

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $template->id ? 'Edit Template' : 'Add Template' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.marketing.templates.index') }}" class="hover-text-primary">Templates</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $template->id ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $template->exists ? route('admin.marketing.templates.update', $template) : route('admin.marketing.templates.store') }}" class="row g-4">
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
					<label class="form-label text-secondary-light mb-2">Template Type <span class="text-danger">*</span></label>
					<ul class="nav nav-pills gap-2" id="templateTypeNav">
						<li class="nav-item">
							<button type="button" class="nav-link {{ $currentType === 'email' ? 'active' : '' }}" data-template-type="email">Email</button>
						</li>
						<li class="nav-item">
							<button type="button" class="nav-link {{ $currentType === 'whatsapp' ? 'active' : '' }}" data-template-type="whatsapp">WhatsApp</button>
						</li>
						<li class="nav-item">
							<button type="button" class="nav-link {{ $currentType === 'push_notification' ? 'active' : '' }}" data-template-type="push_notification">Push Notification</button>
						</li>
					</ul>
					<input type="hidden" name="type" id="template_type" value="{{ $currentType }}">
				</div>

				<div class="col-12" id="subject_field">
					<label class="form-label text-secondary-light">Subject / Title</label>
					<input type="text" name="subject" value="{{ old('subject', $template->subject) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('subject') is-invalid @enderror" placeholder="Used for email or push notifications">
					@error('subject') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12" id="email_layout_field">
					<label class="form-label text-secondary-light">Email Layout</label>
					<select name="email_layout" class="form-select bg-neutral-50 radius-12">
						<option value="basic" @selected(old('email_layout', data_get($meta, 'layout')) === 'basic')>Basic text</option>
						<option value="newsletter" @selected(old('email_layout', data_get($meta, 'layout')) === 'newsletter')>Newsletter</option>
						<option value="transactional" @selected(old('email_layout', data_get($meta, 'layout')) === 'transactional')>Transactional</option>
					</select>
				</div>

				<div class="col-12" id="content_field">
					<label class="form-label text-secondary-light">Content <span class="text-danger">*</span></label>
					<textarea name="content" rows="10" class="form-control bg-neutral-50 radius-12 @error('content') is-invalid @enderror" placeholder="Use @{{variable_name}} for dynamic values.">{{ old('content', $template->type !== 'whatsapp' ? $template->content : '') }}</textarea>
					@error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
					<div class="form-text mt-1">Use @{{variable_name}} syntax for dynamic variables (e.g., @{{customer_name}}, @{{order_number}}).</div>
				</div>

				<div class="col-12" id="whatsapp_body_fields" style="display:none;">
					<div class="row g-3">
						<div class="col-md-4">
							<label class="form-label text-secondary-light">Language</label>
							<select name="language" class="form-select bg-neutral-50 radius-12">
								@foreach($languageOptions as $code => $label)
									<option value="{{ $code }}" @selected(old('language', $template->language ?? 'en') === $code)>{{ $label }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label text-secondary-light">Category</label>
							<select name="category" class="form-select bg-neutral-50 radius-12">
								@foreach($categoryOptions as $value => $label)
									<option value="{{ $value }}" @selected(old('category', $template->category ?? 'MARKETING') === $value)>{{ $label }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label text-secondary-light">CTA Button Text</label>
							<input type="text" name="cta_button_text" value="{{ old('cta_button_text', data_get($meta, 'cta.text')) }}" class="form-control bg-neutral-50 radius-12 h-56-px" placeholder="Optional">
						</div>
						<div class="col-md-12">
							<label class="form-label text-secondary-light">CTA Button URL</label>
							<input type="url" name="cta_button_url" value="{{ old('cta_button_url', data_get($meta, 'cta.url')) }}" class="form-control bg-neutral-50 radius-12 h-56-px" placeholder="https://example.com">
						</div>
						<div class="col-md-12">
							<label class="form-label text-secondary-light">Header</label>
							<textarea name="whatsapp_header" rows="2" class="form-control bg-neutral-50 radius-12">{{ old('whatsapp_header', data_get($meta, 'header')) }}</textarea>
						</div>
						<div class="col-md-12">
							<label class="form-label text-secondary-light">Body <span class="text-danger">*</span></label>
							<textarea name="whatsapp_body" rows="6" class="form-control bg-neutral-50 radius-12 @error('whatsapp_body') is-invalid @enderror" placeholder="Use {{1}}, {{2}} for variables pulled from Meta.">{{ old('whatsapp_body', data_get($meta, 'body')) }}</textarea>
							@error('whatsapp_body') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
						</div>
						<div class="col-md-12">
							<label class="form-label text-secondary-light">Footer</label>
							<textarea name="whatsapp_footer" rows="2" class="form-control bg-neutral-50 radius-12">{{ old('whatsapp_footer', data_get($meta, 'footer')) }}</textarea>
						</div>
					</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Variables (comma separated)</label>
					<input type="text" name="variable_tokens" value="{{ $variableTokens }}" class="form-control bg-neutral-50 radius-12 h-56-px" placeholder="customer_name, order_id">
					<div class="form-text mt-1">Use these placeholders inside your content. They will be replaced when sending.</div>
				</div>

				<div class="col-12" id="whatsapp_meta_fields" style="display:none;">
					<div class="row g-3">
						<div class="col-lg-6">
							<label class="form-label text-secondary-light">WhatsApp Template ID</label>
							<input type="text" name="whatsapp_template_id" value="{{ old('whatsapp_template_id', $template->whatsapp_template_id) }}" class="form-control bg-neutral-50 radius-12 h-56-px">
						</div>
						<div class="col-lg-6">
							<label class="form-label text-secondary-light">WhatsApp Status</label>
							<select name="whatsapp_template_status" class="form-select bg-neutral-50 radius-12 h-56-px">
								<option value="">Not set</option>
								<option value="pending" @selected(old('whatsapp_template_status', $template->whatsapp_template_status) === 'pending')>Pending</option>
								<option value="approved" @selected(old('whatsapp_template_status', $template->whatsapp_template_status) === 'approved')>Approved</option>
								<option value="rejected" @selected(old('whatsapp_template_status', $template->whatsapp_template_status) === 'rejected')>Rejected</option>
							</select>
						</div>
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
					<a href="{{ route('admin.marketing.templates.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

@push('scripts')
<script>
const typeNavButtons = document.querySelectorAll('[data-template-type]');
const typeField = document.getElementById('template_type');

function setTemplateType(type) {
	if (!typeField) return;
	typeField.value = type;

	typeNavButtons.forEach(btn => {
		btn.classList.toggle('active', btn.dataset.templateType === type);
	});

	const subjectField = document.getElementById('subject_field');
	const layoutField = document.getElementById('email_layout_field');
	const contentField = document.getElementById('content_field');
	const whatsappFields = document.getElementById('whatsapp_body_fields');
	const whatsappMeta = document.getElementById('whatsapp_meta_fields');

	if (subjectField) {
		subjectField.style.display = type === 'whatsapp' ? 'none' : 'block';
		const label = subjectField.querySelector('label');
		if (label) {
			label.textContent = type === 'push_notification' ? 'Title' : 'Subject';
		}
	}

	if (layoutField) {
		layoutField.style.display = type === 'email' ? 'block' : 'none';
	}

	if (contentField) {
		contentField.style.display = type === 'whatsapp' ? 'none' : 'block';
		const textarea = contentField.querySelector('textarea');
		if (textarea) {
			textarea.required = type !== 'whatsapp';
		}
	}

	if (whatsappFields) {
		whatsappFields.style.display = type === 'whatsapp' ? 'block' : 'none';
	}

	if (whatsappMeta) {
		whatsappMeta.style.display = type === 'whatsapp' ? 'block' : 'none';
	}
}

typeNavButtons.forEach(btn => {
	btn.addEventListener('click', () => setTemplateType(btn.dataset.templateType));
});

document.addEventListener('DOMContentLoaded', function() {
	setTemplateType(typeField?.value || 'email');
});
</script>
@endpush
@endsection

