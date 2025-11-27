

<?php $__env->startSection('title', $campaign->exists ? 'Edit Drip Campaign' : 'Create Drip Campaign'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0"><?php echo e($campaign->exists ? 'Edit Drip Campaign' : 'Create Drip Campaign'); ?></h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.marketing.drip-campaigns.index')); ?>" class="hover-text-primary">Drip Campaigns</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light"><?php echo e($campaign->exists ? 'Edit' : 'Create'); ?></li>
		</ul>
	</div>

	<form method="POST" action="<?php echo e($campaign->exists ? route('admin.marketing.drip-campaigns.update', $campaign) : route('admin.marketing.drip-campaigns.store')); ?>" id="dripCampaignForm">
		<?php echo csrf_field(); ?>
		<?php if($campaign->exists): ?>
			<?php echo method_field('PUT'); ?>
		<?php endif; ?>

		<div class="card border-0 mb-24">
			<div class="card-body p-24">
				<h6 class="fw-semibold mb-16">Campaign Details</h6>
				<div class="row g-4">
					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Campaign Name <span class="text-danger">*</span></label>
						<input type="text" name="name" value="<?php echo e(old('name', $campaign->name)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
						<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Trigger Type <span class="text-danger">*</span></label>
						<select name="trigger_type" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['trigger_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
							<option value="new_enquiry" <?php if(old('trigger_type', $campaign->trigger_type) === 'new_enquiry'): echo 'selected'; endif; ?>>New Enquiry</option>
							<option value="new_lead" <?php if(old('trigger_type', $campaign->trigger_type) === 'new_lead'): echo 'selected'; endif; ?>>New Lead</option>
							<option value="manual" <?php if(old('trigger_type', $campaign->trigger_type) === 'manual'): echo 'selected'; endif; ?>>Manual Trigger</option>
						</select>
						<?php $__errorArgs = ['trigger_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Channel <span class="text-danger">*</span></label>
						<select name="channel" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['channel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
							<option value="email" <?php if(old('channel', $campaign->channel) === 'email'): echo 'selected'; endif; ?>>Email</option>
							<option value="whatsapp" <?php if(old('channel', $campaign->channel) === 'whatsapp'): echo 'selected'; endif; ?>>WhatsApp</option>
							<option value="both" <?php if(old('channel', $campaign->channel) === 'both'): echo 'selected'; endif; ?>>Both</option>
						</select>
						<?php $__errorArgs = ['channel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Status</label>
						<div class="form-check form-switch mt-2">
							<input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="is_active" <?php echo e(old('is_active', $campaign->is_active ?? true) ? 'checked' : ''); ?>>
							<label class="form-check-label" for="is_active">Active</label>
						</div>
					</div>

					<div class="col-12">
						<label class="form-label text-secondary-light">Description</label>
						<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $campaign->description)); ?></textarea>
						<?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="card border-0 mb-24">
			<div class="card-body p-24">
				<div class="d-flex justify-content-between align-items-center mb-16">
					<h6 class="fw-semibold mb-0">Campaign Steps</h6>
					<button type="button" class="btn btn-primary btn-sm" id="addStepBtn">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add Step
					</button>
				</div>

				<div id="stepsContainer">
					<?php if($campaign->exists && $campaign->steps->count() > 0): ?>
						<?php $__currentLoopData = $campaign->steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php echo $__env->make('admin.marketing.drip-campaigns.partials.step-row', ['step' => $step, 'index' => $index, 'templates' => $templates], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php else: ?>
						<?php echo $__env->make('admin.marketing.drip-campaigns.partials.step-row', ['step' => null, 'index' => 0, 'templates' => $templates], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="d-flex gap-2">
			<button type="submit" class="btn btn-primary radius-12 px-24"><?php echo e($campaign->exists ? 'Update Campaign' : 'Create Campaign'); ?></button>
			<a href="<?php echo e(route('admin.marketing.drip-campaigns.index')); ?>" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
		</div>
	</form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
	let stepIndex = <?php echo e($campaign->exists && $campaign->steps->count() > 0 ? $campaign->steps->count() : 1); ?>;
	const templates = <?php echo json_encode($templates, 15, 512) ?>;

	document.getElementById('addStepBtn').addEventListener('click', function() {
		const container = document.getElementById('stepsContainer');
		const stepRow = document.createElement('div');
		stepRow.className = 'step-row border radius-12 p-3 mb-3';
		stepRow.innerHTML = getStepRowHtml(stepIndex);
		container.appendChild(stepRow);
		stepIndex++;
	});

	document.addEventListener('click', function(e) {
		if (e.target.classList.contains('remove-step')) {
			e.target.closest('.step-row').remove();
		}
	});

	function getStepRowHtml(index) {
		let templateOptions = '';
		templates.forEach(template => {
			templateOptions += `<option value="${template.id}">${template.name} (${template.type})</option>`;
		});

		return `
			<div class="row g-3 align-items-end">
				<div class="col-md-1">
					<label class="form-label text-secondary-light">Step #</label>
					<input type="number" name="steps[${index}][step_number]" value="${index + 1}" class="form-control bg-neutral-50 radius-12" required min="1">
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">Delay (Hours)</label>
					<input type="number" name="steps[${index}][delay_hours]" value="0" class="form-control bg-neutral-50 radius-12" required min="0">
				</div>
				<div class="col-md-3">
					<label class="form-label text-secondary-light">Template</label>
					<select name="steps[${index}][template_id]" class="form-select bg-neutral-50 radius-12" required>
						<option value="">Select Template</option>
						${templateOptions}
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">Channel</label>
					<select name="steps[${index}][channel]" class="form-select bg-neutral-50 radius-12" required>
						<option value="email">Email</option>
						<option value="whatsapp">WhatsApp</option>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">Status</label>
					<div class="form-check form-switch mt-2">
						<input class="form-check-input" type="checkbox" name="steps[${index}][is_active]" value="1" checked>
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
		`;
	}
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/marketing/drip-campaigns/form.blade.php ENDPATH**/ ?>