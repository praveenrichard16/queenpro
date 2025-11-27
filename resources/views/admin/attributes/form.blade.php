@extends('layouts.admin')

@section('title', $attribute->exists ? 'Edit Attribute' : 'Add Attribute')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">{{ $attribute->exists ? 'Edit Attribute' : 'Add Attribute' }}</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.attributes.index') }}" class="hover-text-primary">Attributes</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">{{ $attribute->exists ? 'Edit' : 'Create' }}</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="{{ $attribute->exists ? route('admin.attributes.update', $attribute) : route('admin.attributes.store') }}" class="row g-4" enctype="multipart/form-data" id="attributeForm">
				@csrf
				@if($attribute->exists)
					@method('PUT')
				@endif

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Attribute Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="{{ old('name', $attribute->name) }}" class="form-control bg-neutral-50 radius-12 h-56-px @error('name') is-invalid @enderror" required>
					@error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Type <span class="text-danger">*</span></label>
					<select name="type" id="attributeType" class="form-select bg-neutral-50 radius-12 h-56-px @error('type') is-invalid @enderror" required>
						<option value="select" @selected(old('type', $attribute->type) === 'select')>Select (Dropdown)</option>
						<option value="text" @selected(old('type', $attribute->type) === 'text')>Text (Custom Input)</option>
						<option value="color" @selected(old('type', $attribute->type) === 'color')>Color</option>
						<option value="image" @selected(old('type', $attribute->type) === 'image')>Image</option>
					</select>
					@error('type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>

				<div class="col-12">
					<div class="form-check style-check d-flex align-items-center">
						<input class="form-check-input border border-neutral-300" type="checkbox" value="1" id="is_active" name="is_active" @checked(old('is_active', $attribute->is_active ?? true))>
						<label class="form-check-label ms-8" for="is_active">
							Active
						</label>
					</div>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h6 class="fw-semibold mb-0">Attribute Values</h6>
						<button type="button" class="btn btn-sm btn-outline-primary" id="addValueBtn">
							<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
							Add Value
						</button>
					</div>
					<div id="valuesContainer">
						@if($attribute->exists && $attribute->values->isNotEmpty())
							@foreach($attribute->values as $index => $value)
								@include('admin.attributes.partials.value-row', ['index' => $index, 'value' => $value, 'attribute' => $attribute])
							@endforeach
						@else
							@include('admin.attributes.partials.value-row', ['index' => 0, 'value' => null, 'attribute' => $attribute])
						@endif
					</div>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button type="submit" class="btn btn-primary radius-12 px-24">{{ $attribute->exists ? 'Update Attribute' : 'Create Attribute' }}</button>
					<a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
let valueIndex = {{ $attribute->exists && $attribute->values->isNotEmpty() ? $attribute->values->count() : 1 }};

document.getElementById('addValueBtn').addEventListener('click', function() {
	const container = document.getElementById('valuesContainer');
	const type = document.getElementById('attributeType').value;
	const row = createValueRow(valueIndex++, type);
	container.appendChild(row);
});

function createValueRow(index, type) {
	const row = document.createElement('div');
	row.className = 'value-row border rounded p-3 mb-3';
	row.innerHTML = `
		<div class="row g-3">
			<div class="col-md-3">
				<label class="form-label small">Value <span class="text-danger">*</span></label>
				<input type="text" name="values[${index}][value]" class="form-control form-control-sm" required>
			</div>
			<div class="col-md-3">
				<label class="form-label small">Display Value</label>
				<input type="text" name="values[${index}][display_value]" class="form-control form-control-sm" placeholder="Optional">
			</div>
			${type === 'color' ? `
			<div class="col-md-2">
				<label class="form-label small">Color Code</label>
				<input type="color" name="values[${index}][color_code]" class="form-control form-control-sm" style="height: 38px;">
			</div>
			` : ''}
			${type === 'image' ? `
			<div class="col-md-2">
				<label class="form-label small">Image</label>
				<input type="file" name="values[${index}][image]" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.webp">
			</div>
			` : ''}
			<div class="col-md-2">
				<label class="form-label small">Sort Order</label>
				<input type="number" name="values[${index}][sort_order]" value="${index}" class="form-control form-control-sm">
			</div>
			<div class="col-md-2 d-flex align-items-end">
				<button type="button" class="btn btn-sm btn-outline-danger w-100 remove-value">
					<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
					Remove
				</button>
			</div>
		</div>
	`;
	
	row.querySelector('.remove-value').addEventListener('click', function() {
		row.remove();
	});
	
	return row;
}

document.getElementById('attributeType').addEventListener('change', function() {
	const type = this.value;
	const rows = document.querySelectorAll('.value-row');
	rows.forEach(row => {
		const colorField = row.querySelector('input[name*="[color_code]"]');
		const imageField = row.querySelector('input[name*="[image]"]');
		const colorCol = colorField?.closest('.col-md-2');
		const imageCol = imageField?.closest('.col-md-2');
		
		if (type === 'color' && !colorField) {
			const col = document.createElement('div');
			col.className = 'col-md-2';
			col.innerHTML = `
				<label class="form-label small">Color Code</label>
				<input type="color" name="${row.querySelector('input[name*="[value]"]').name.replace('[value]', '[color_code]')}" class="form-control form-control-sm" style="height: 38px;">
			`;
			row.querySelector('.row').insertBefore(col, row.querySelector('.col-md-2:last-child'));
		} else if (colorCol && type !== 'color') {
			colorCol.remove();
		}
		
		if (type === 'image' && !imageField) {
			const col = document.createElement('div');
			col.className = 'col-md-2';
			col.innerHTML = `
				<label class="form-label small">Image</label>
				<input type="file" name="${row.querySelector('input[name*="[value]"]').name.replace('[value]', '[image]')}" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.webp">
			`;
			row.querySelector('.row').insertBefore(col, row.querySelector('.col-md-2:last-child'));
		} else if (imageCol && type !== 'image') {
			imageCol.remove();
		}
	});
});

document.addEventListener('click', function(e) {
	if (e.target.closest('.remove-value')) {
		e.target.closest('.value-row').remove();
	}
});
</script>
@endsection

