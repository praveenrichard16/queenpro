@extends('layouts.admin')

@section('title', 'Import Locations')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Import Locations</h6>
			<p class="text-secondary-light mb-0">Import locations from CSV or JSON file.</p>
		</div>
		<a href="{{ route('admin.settings.locations.index') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
			Back to locations
		</a>
	</div>

	@if(session('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ session('error') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-sm mb-24">
		<div class="card-body p-24">
			<form action="{{ route('admin.settings.locations.import.process') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="row g-4">
					<div class="col-12">
						<label class="form-label text-secondary-light">Import Mode *</label>
						<select name="import_mode" class="form-select bg-neutral-50 radius-12 @error('import_mode') is-invalid @enderror" required>
							<option value="create">Create New Only (Skip Existing)</option>
							<option value="update">Update Existing (Match by Postal Code)</option>
							<option value="replace">Replace All (Delete All Then Import)</option>
						</select>
						<small class="text-secondary-light d-block mt-2">
							<strong>Create:</strong> Only adds new locations, skips duplicates.<br>
							<strong>Update:</strong> Updates existing locations by postal code, creates new ones if not found.<br>
							<strong>Replace:</strong> Deletes all existing locations and imports fresh data.
						</small>
						@error('import_mode') <div class="invalid-feedback">{{ $message }}</div> @enderror
					</div>
					<div class="col-12">
						<label class="form-label text-secondary-light">File *</label>
						<input type="file" name="file" class="form-control bg-neutral-50 radius-12 @error('file') is-invalid @enderror" accept=".csv,.txt,.json" required>
						<small class="text-secondary-light d-block mt-2">
							Accepted formats: CSV, TXT, JSON. Maximum file size: 10MB.<br>
							CSV should have headers: region_name_en, region_name_ar, city_name_en, city_name_ar, district_name_en, district_name_ar, postal_code, latitude, longitude, is_active
						</small>
						@error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
					</div>
					<div class="col-12">
						<button class="btn btn-primary radius-12 px-24" type="submit">
							<iconify-icon icon="solar:import-outline"></iconify-icon>
							Import Locations
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			<h6 class="fw-semibold mb-16">Import Instructions</h6>
			<div class="text-secondary-light">
				<h6 class="fw-semibold mb-8">CSV Format:</h6>
				<p class="mb-16">Your CSV file should include the following columns (headers required):</p>
				<ul class="mb-16">
					<li><code>region_name_en</code> - Region name in English (required)</li>
					<li><code>region_name_ar</code> - Region name in Arabic (optional)</li>
					<li><code>city_name_en</code> - City name in English (required)</li>
					<li><code>city_name_ar</code> - City name in Arabic (optional)</li>
					<li><code>district_name_en</code> - District name in English (optional)</li>
					<li><code>district_name_ar</code> - District name in Arabic (optional)</li>
					<li><code>postal_code</code> - Postal code (optional)</li>
					<li><code>latitude</code> - Latitude coordinate (optional)</li>
					<li><code>longitude</code> - Longitude coordinate (optional)</li>
					<li><code>is_active</code> - Active status (1 or 0, optional, defaults to 1)</li>
				</ul>

				<h6 class="fw-semibold mb-8">JSON Format:</h6>
				<p class="mb-8">Your JSON file should be an array of objects with the same fields as CSV columns.</p>
				<pre class="bg-neutral-50 p-16 radius-12 mb-0"><code>[
  {
    "region_name_en": "Riyadh",
    "city_name_en": "Riyadh",
    "postal_code": "11564",
    "is_active": true
  }
]</code></pre>
			</div>
		</div>
	</div>
</div>
@endsection

