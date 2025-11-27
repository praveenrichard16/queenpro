@extends('layouts.admin')

@section('title', 'Breadcrumb Settings')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Breadcrumb Settings</h6>
			<p class="text-secondary-light mb-0">Configure background image and styling for breadcrumb sections.</p>
		</div>
		<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
			Back to Dashboard
		</a>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	@if($errors->any())
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<ul class="mb-0">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			<form action="{{ route('admin.cms.breadcrumb.settings.update') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="row g-4">
					<div class="col-12">
						<label class="form-label text-secondary-light fw-semibold">Background Image</label>
						@if($settings['breadcrumb_background_image'])
							<div class="mb-3">
								<img src="{{ \Illuminate\Support\Facades\Storage::url($settings['breadcrumb_background_image']) }}" alt="Breadcrumb background" class="img-thumbnail" style="max-width: 400px; max-height: 200px; object-fit: cover;">
								<div class="mt-2">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="remove_background_image" value="1" id="remove_background_image">
										<label class="form-check-label text-danger" for="remove_background_image">
											Remove current image
										</label>
									</div>
								</div>
							</div>
						@endif
						<input type="file" name="breadcrumb_background_image" class="form-control bg-neutral-50 radius-12 @error('breadcrumb_background_image') is-invalid @enderror" accept="image/jpeg,image/jpg,image/png,image/webp">
						<small class="text-secondary-light d-block mt-2">Recommended size: 1920x300px or larger. Max file size: 5MB. Formats: JPEG, PNG, WebP</small>
						@error('breadcrumb_background_image')
							<div class="invalid-feedback d-block">{{ $message }}</div>
						@enderror
					</div>

					<div class="col-md-6">
						<label class="form-label text-secondary-light fw-semibold">Overlay Opacity</label>
						<input type="number" name="breadcrumb_overlay_opacity" step="0.1" min="0" max="1" class="form-control bg-neutral-50 radius-12 @error('breadcrumb_overlay_opacity') is-invalid @enderror" value="{{ old('breadcrumb_overlay_opacity', $settings['breadcrumb_overlay_opacity']) }}" placeholder="0.5">
						<small class="text-secondary-light d-block mt-2">Opacity of dark overlay on background image (0 = transparent, 1 = fully opaque). Default: 0.5</small>
						@error('breadcrumb_overlay_opacity')
							<div class="invalid-feedback d-block">{{ $message }}</div>
						@enderror
					</div>

					<div class="col-md-6">
						<label class="form-label text-secondary-light fw-semibold">Background Color (Fallback)</label>
						<div class="input-group">
							<input type="text" name="breadcrumb_background_color" class="form-control bg-neutral-50 radius-12 @error('breadcrumb_background_color') is-invalid @enderror" value="{{ old('breadcrumb_background_color', $settings['breadcrumb_background_color']) }}" placeholder="#f3f4f6">
							<input type="color" class="form-control form-control-color" style="width: 60px;" value="{{ old('breadcrumb_background_color', $settings['breadcrumb_background_color'] ?: '#f3f4f6') }}" onchange="this.previousElementSibling.value = this.value">
						</div>
						<small class="text-secondary-light d-block mt-2">Fallback background color if no image is set. Default: #f3f4f6</small>
						@error('breadcrumb_background_color')
							<div class="invalid-feedback d-block">{{ $message }}</div>
						@enderror
					</div>

					<div class="col-12">
						<button type="submit" class="btn btn-primary radius-12 px-24">
							Save Settings
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

