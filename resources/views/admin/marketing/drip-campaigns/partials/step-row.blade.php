@php
	$waitOptions = $waitOptions ?? ['' => 'No wait condition'];
	$conditionsValue = old("steps.{$index}.conditions");
	if ($conditionsValue === null && isset($step) && $step?->conditions) {
		$conditionsValue = json_encode($step->conditions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
@endphp

<div class="step-row border radius-12 p-3 mb-3">
	<div class="row g-3 align-items-end">
		<div class="col-md-1">
			<label class="form-label text-secondary-light">Step #</label>
			<input type="number" name="steps[{{ $index }}][step_number]" value="{{ old("steps.{$index}.step_number", $step->step_number ?? $index + 1) }}" class="form-control bg-neutral-50 radius-12" required min="1">
		</div>
		<div class="col-md-2">
			<label class="form-label text-secondary-light">Delay (Hours)</label>
			<input type="number" name="steps[{{ $index }}][delay_hours]" value="{{ old("steps.{$index}.delay_hours", $step->delay_hours ?? 0) }}" class="form-control bg-neutral-50 radius-12" required min="0">
		</div>
		<div class="col-md-3">
			<label class="form-label text-secondary-light">Template</label>
			<select name="steps[{{ $index }}][template_id]" class="form-select bg-neutral-50 radius-12" required>
				<option value="">Select Template</option>
				@foreach($templates as $template)
					<option value="{{ $template->id }}" @selected(old("steps.{$index}.template_id", $step->template_id ?? null) == $template->id)>
						{{ $template->name }} ({{ $template->type }})
					</option>
				@endforeach
			</select>
		</div>
		<div class="col-md-2">
			<label class="form-label text-secondary-light">Channel</label>
			<select name="steps[{{ $index }}][channel]" class="form-select bg-neutral-50 radius-12" required>
				<option value="email" @selected(old("steps.{$index}.channel", $step->channel ?? 'email') === 'email')>Email</option>
				<option value="whatsapp" @selected(old("steps.{$index}.channel", $step->channel ?? 'email') === 'whatsapp')>WhatsApp</option>
			</select>
		</div>
		<div class="col-md-2">
			<label class="form-label text-secondary-light">Status</label>
			<div class="form-check form-switch mt-2">
				<input class="form-check-input" type="checkbox" name="steps[{{ $index }}][is_active]" value="1" {{ old("steps.{$index}.is_active", $step->is_active ?? true) ? 'checked' : '' }}>
				<label class="form-check-label">Active</label>
			</div>
		</div>
		<div class="col-md-3">
			<label class="form-label text-secondary-light">Wait Condition</label>
			<select name="steps[{{ $index }}][wait_until_event]" class="form-select bg-neutral-50 radius-12">
				@foreach($waitOptions as $value => $label)
					<option value="{{ $value }}" @selected(old("steps.{$index}.wait_until_event", $step->wait_until_event ?? '') === $value)>{{ $label }}</option>
				@endforeach
			</select>
		</div>
		<div class="col-12">
			<label class="form-label text-secondary-light">Conditions (JSON or key:value per line)</label>
			<textarea name="steps[{{ $index }}][conditions]" rows="2" class="form-control bg-neutral-50 radius-12" placeholder='{"lead_stage":"proposal"}'>{{ $conditionsValue }}</textarea>
			<small class="text-secondary-light">Use JSON or lines like <code>lead_stage:proposal</code>.</small>
		</div>
		<div class="col-md-2">
			<button type="button" class="btn btn-danger btn-sm remove-step">
				<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
				Remove
			</button>
		</div>
	</div>
</div>

