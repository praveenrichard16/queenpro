

<?php $__env->startSection('title', 'Drip Campaign Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0"><?php echo e($dripCampaign->name); ?></h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.marketing.drip-campaigns.index')); ?>" class="hover-text-primary">Drip Campaigns</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Details</li>
		</ul>
	</div>

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Campaign Steps</h6>
					<?php $__currentLoopData = $dripCampaign->steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<div class="border radius-12 p-3 mb-3">
							<div class="d-flex justify-content-between align-items-start">
								<div>
									<h6 class="fw-semibold mb-1">Step <?php echo e($step->step_number); ?></h6>
									<p class="text-sm text-secondary-light mb-1">
										Delay: <?php echo e($step->delay_hours); ?> hours | 
										Channel: <?php echo e(ucfirst($step->channel)); ?> | 
										Template: <?php echo e($step->template->name ?? 'N/A'); ?>

									</p>
								</div>
								<span class="badge bg-<?php echo e($step->is_active ? 'success' : 'secondary'); ?>">
									<?php echo e($step->is_active ? 'Active' : 'Inactive'); ?>

								</span>
							</div>
						</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Campaign Info</h6>
					<div class="mb-3">
						<label class="text-secondary-light small">Trigger Type</label>
						<div class="fw-medium"><?php echo e(ucfirst(str_replace('_', ' ', $dripCampaign->trigger_type))); ?></div>
					</div>
					<div class="mb-3">
						<label class="text-secondary-light small">Channel</label>
						<div class="fw-medium"><?php echo e(ucfirst($dripCampaign->channel)); ?></div>
					</div>
					<div class="mb-3">
						<label class="text-secondary-light small">Status</label>
						<div>
							<span class="badge bg-<?php echo e($dripCampaign->is_active ? 'success' : 'secondary'); ?>">
								<?php echo e($dripCampaign->is_active ? 'Active' : 'Inactive'); ?>

							</span>
						</div>
					</div>
					<div class="mb-3">
						<label class="text-secondary-light small">Total Recipients</label>
						<div class="fw-medium"><?php echo e($dripCampaign->recipients->count()); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/marketing/drip-campaigns/show.blade.php ENDPATH**/ ?>