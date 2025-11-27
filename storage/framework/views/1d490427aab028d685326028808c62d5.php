<div class="step-row border radius-12 p-3 mb-3">
	<div class="row g-3 align-items-end">
		<div class="col-md-1">
			<label class="form-label text-secondary-light">Step #</label>
			<input type="number" name="steps[<?php echo e($index); ?>][step_number]" value="<?php echo e(old("steps.{$index}.step_number", $step->step_number ?? $index + 1)); ?>" class="form-control bg-neutral-50 radius-12" required min="1">
		</div>
		<div class="col-md-2">
			<label class="form-label text-secondary-light">Delay (Hours)</label>
			<input type="number" name="steps[<?php echo e($index); ?>][delay_hours]" value="<?php echo e(old("steps.{$index}.delay_hours", $step->delay_hours ?? 0)); ?>" class="form-control bg-neutral-50 radius-12" required min="0">
		</div>
		<div class="col-md-3">
			<label class="form-label text-secondary-light">Template</label>
			<select name="steps[<?php echo e($index); ?>][template_id]" class="form-select bg-neutral-50 radius-12" required>
				<option value="">Select Template</option>
				<?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($template->id); ?>" <?php if(old("steps.{$index}.template_id", $step->template_id ?? null) == $template->id): echo 'selected'; endif; ?>>
						<?php echo e($template->name); ?> (<?php echo e($template->type); ?>)
					</option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
		<div class="col-md-2">
			<label class="form-label text-secondary-light">Channel</label>
			<select name="steps[<?php echo e($index); ?>][channel]" class="form-select bg-neutral-50 radius-12" required>
				<option value="email" <?php if(old("steps.{$index}.channel", $step->channel ?? 'email') === 'email'): echo 'selected'; endif; ?>>Email</option>
				<option value="whatsapp" <?php if(old("steps.{$index}.channel", $step->channel ?? 'email') === 'whatsapp'): echo 'selected'; endif; ?>>WhatsApp</option>
			</select>
		</div>
		<div class="col-md-2">
			<label class="form-label text-secondary-light">Status</label>
			<div class="form-check form-switch mt-2">
				<input class="form-check-input" type="checkbox" name="steps[<?php echo e($index); ?>][is_active]" value="1" <?php echo e(old("steps.{$index}.is_active", $step->is_active ?? true) ? 'checked' : ''); ?>>
				<label class="form-check-label">Active</label>
			</div>
		</div>
		<div class="col-md-2">
			<button type="button" class="btn btn-danger btn-sm remove-step">
				<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
				Remove
			</button>
		</div>
	</div>
</div>

<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/marketing/drip-campaigns/partials/step-row.blade.php ENDPATH**/ ?>