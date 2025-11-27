

<?php $__env->startSection('title', 'Marketing Campaigns'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Marketing Campaigns</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Campaigns</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<div class="d-flex justify-content-end">
				<a href="<?php echo e(route('admin.marketing.campaigns.create')); ?>" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
					<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					Create Campaign
				</a>
			</div>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Campaign</th>
							<th>Template</th>
							<th>Recipients</th>
							<th>Status</th>
							<th>Sent</th>
							<th>Date</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium"><?php echo e($campaign->name); ?></h6>
								</td>
								<td>
									<span class="text-sm"><?php echo e($campaign->template->name); ?></span>
								</td>
								<td>
									<span class="fw-semibold"><?php echo e($campaign->total_recipients); ?></span>
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm <?php echo e(match($campaign->status) {
										'completed' => 'bg-success-focus text-success-main',
										'sending' => 'bg-info-focus text-info-main',
										'scheduled' => 'bg-warning-focus text-warning-main',
										default => 'bg-neutral-200 text-neutral-600'
									}); ?>">
										<?php echo e(ucfirst($campaign->status)); ?>

									</span>
								</td>
								<td>
									<span class="text-sm"><?php echo e($campaign->sent_count); ?>/<?php echo e($campaign->total_recipients); ?></span>
								</td>
								<td>
									<span class="text-sm text-secondary-light"><?php echo e($campaign->created_at->format('M d, Y')); ?></span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.marketing.campaigns.show', $campaign)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="solar:eye-bold"></iconify-icon>
										</a>
										<?php if($campaign->status === 'draft' || $campaign->status === 'scheduled'): ?>
											<form action="<?php echo e(route('admin.marketing.campaigns.send', $campaign)); ?>" method="POST" onsubmit="return confirm('Send this campaign?')" class="d-inline">
												<?php echo csrf_field(); ?>
												<button type="submit" class="w-36-px h-36-px bg-success-focus text-success-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center">
													<iconify-icon icon="solar:plain-2-outline"></iconify-icon>
												</button>
											</form>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="7" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No campaigns" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No campaigns found</h6>
									<p class="text-secondary-light mb-0">Create your first marketing campaign.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($campaigns->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($campaigns->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/marketing/campaigns/index.blade.php ENDPATH**/ ?>