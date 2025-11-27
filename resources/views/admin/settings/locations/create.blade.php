@extends('layouts.admin')

@section('title', 'Create Location')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Create Location</h6>
			<p class="text-secondary-light mb-0">Add a new location to the India locations database.</p>
		</div>
		<a href="{{ route('admin.settings.locations.index') }}" class="btn btn-outline-secondary radius-12 px-24 d-flex align-items-center gap-2">
			<iconify-icon icon="solar:arrow-left-linear" class="text-lg"></iconify-icon>
			Back to locations
		</a>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-24">
			<form action="{{ route('admin.settings.locations.store') }}" method="POST" class="row g-4">
				@csrf
				<div class="col-md-6">
					<label class="form-label text-secondary-light">State Name *</label>
					<input type="text" 
						   name="region_name_en" 
						   id="region_name_en" 
						   class="form-control bg-neutral-50 radius-12 location-search-input @error('region_name_en') is-invalid @enderror" 
						   list="region_list"
						   placeholder="Search or select state"
						   autocomplete="off"
						   value="{{ old('region_name_en') }}" 
						   required>
					<datalist id="region_list"></datalist>
					@error('region_name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-6">
					<label class="form-label text-secondary-light">City Name *</label>
					<input type="text" 
						   name="city_name_en" 
						   id="city_name_en" 
						   class="form-control bg-neutral-50 radius-12 location-search-input @error('city_name_en') is-invalid @enderror" 
						   list="city_list"
						   placeholder="Search or select city"
						   autocomplete="off"
						   value="{{ old('city_name_en') }}" 
						   required>
					<datalist id="city_list"></datalist>
					@error('city_name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-6">
					<label class="form-label text-secondary-light">District Name</label>
					<input type="text" name="district_name_en" class="form-control bg-neutral-50 radius-12 @error('district_name_en') is-invalid @enderror" value="{{ old('district_name_en') }}">
					@error('district_name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-4">
					<label class="form-label text-secondary-light">Postal Code</label>
					<input type="text" 
						   name="postal_code" 
						   id="postal_code" 
						   class="form-control bg-neutral-50 radius-12 @error('postal_code') is-invalid @enderror" 
						   value="{{ old('postal_code') }}" 
						   maxlength="10"
						   placeholder="Auto-filled from city">
					@error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-4">
					<label class="form-label text-secondary-light">Latitude</label>
					<input type="number" step="any" name="latitude" class="form-control bg-neutral-50 radius-12 @error('latitude') is-invalid @enderror" value="{{ old('latitude') }}" min="-90" max="90">
					@error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-md-4">
					<label class="form-label text-secondary-light">Longitude</label>
					<input type="number" step="any" name="longitude" class="form-control bg-neutral-50 radius-12 @error('longitude') is-invalid @enderror" value="{{ old('longitude') }}" min="-180" max="180">
					@error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
				</div>
				<div class="col-12">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" id="location-active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
						<label class="form-check-label text-secondary-light" for="location-active">Location is active</label>
					</div>
				</div>
				<div class="col-12">
					<button class="btn btn-primary radius-12 px-24" type="submit">Create Location</button>
				</div>
			</form>
		</div>
	</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let statesData = [];
    let citiesData = [];
    
    // Load states on page load
    fetch('{{ route("api.india.states") }}')
        .then(response => response.json())
        .then(data => {
            statesData = data;
            const regionList = document.getElementById('region_list');
            regionList.innerHTML = '';
            data.forEach(state => {
                const option = document.createElement('option');
                option.value = state;
                regionList.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading states:', error));
    
    // Load cities when region changes
    document.getElementById('region_name_en').addEventListener('input', function() {
        const region = this.value;
        if (region && statesData.includes(region)) {
            loadCities(region);
        }
    });
    
    function loadCities(region) {
        fetch(`{{ route("api.india.cities") }}?state=${encodeURIComponent(region)}`)
            .then(response => response.json())
            .then(data => {
                citiesData = data;
                const cityList = document.getElementById('city_list');
                cityList.innerHTML = '';
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    cityList.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading cities:', error));
    }
    
    // Auto-fill postal code when city is selected
    document.getElementById('city_name_en').addEventListener('input', function() {
        const city = this.value;
        const region = document.getElementById('region_name_en').value;
        if (city && region) {
            loadPostalCode(city, region);
        }
    });
    
    function loadPostalCode(city, region) {
        fetch(`{{ route("api.india.postal-codes") }}?city=${encodeURIComponent(city)}&state=${encodeURIComponent(region)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    document.getElementById('postal_code').value = data[0];
                }
            })
            .catch(error => console.error('Error loading postal codes:', error));
    }
});
</script>
@endpush
@endsection

