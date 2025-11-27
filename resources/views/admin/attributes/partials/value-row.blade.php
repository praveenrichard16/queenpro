<div class="value-row border rounded p-3 mb-3">
	<div class="row g-3">
		@if($value)
			<input type="hidden" name="values[{{ $index }}][id]" value="{{ $value->id }}">
		@endif
		<div class="col-md-3">
			<label class="form-label small">Value <span class="text-danger">*</span></label>
			<input type="text" name="values[{{ $index }}][value]" value="{{ old("values.{$index}.value", $value->value ?? '') }}" class="form-control form-control-sm" required>
		</div>
		<div class="col-md-3">
			<label class="form-label small">Display Value</label>
			<input type="text" name="values[{{ $index }}][display_value]" value="{{ old("values.{$index}.display_value", $value->display_value ?? '') }}" class="form-control form-control-sm" placeholder="Optional">
		</div>
		@if($attribute->type === 'color' || old('type', $attribute->type) === 'color')
		<div class="col-md-2">
			<label class="form-label small">Color Code</label>
			<input type="color" name="values[{{ $index }}][color_code]" value="{{ old("values.{$index}.color_code", $value->color_code ?? '#000000') }}" class="form-control form-control-sm" style="height: 38px;">
		</div>
		@endif
		@if($attribute->type === 'image' || old('type', $attribute->type) === 'image')
		<div class="col-md-2">
			<label class="form-label small">Image</label>
			<input type="file" name="values[{{ $index }}][image]" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.webp">
			@if($value && $value->image_path)
				<small class="text-muted d-block mt-1">Current: <img src="{{ $value->image_path }}" alt="" style="max-height: 30px;"></small>
			@endif
		</div>
		@endif
		<div class="col-md-2">
			<label class="form-label small">Sort Order</label>
			<input type="number" name="values[{{ $index }}][sort_order]" value="{{ old("values.{$index}.sort_order", $value->sort_order ?? $index) }}" class="form-control form-control-sm">
		</div>
		<div class="col-md-2 d-flex align-items-end">
			<button type="button" class="btn btn-sm btn-outline-danger w-100 remove-value">
				<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
				Remove
			</button>
		</div>
	</div>
</div>

