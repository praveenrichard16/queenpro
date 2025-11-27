

<?php $__env->startSection('title', 'Marketing Templates'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Marketing Templates</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Marketing Templates</li>
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
			<div class="d-flex justify-content-between align-items-center">
				<form method="GET" class="d-flex gap-3">
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="type" onchange="this.form.submit()">
						<option value="">All types</option>
						<option value="email" <?php if(request('type') === 'email'): echo 'selected'; endif; ?>>Email</option>
						<option value="whatsapp" <?php if(request('type') === 'whatsapp'): echo 'selected'; endif; ?>>WhatsApp</option>
						<option value="push_notification" <?php if(request('type') === 'push_notification'): echo 'selected'; endif; ?>>Push Notification</option>
					</select>
				</form>
				<div class="d-flex gap-2">
					<?php if(request('type') === 'whatsapp'): ?>
						<form action="<?php echo e(route('admin.marketing.templates.sync-whatsapp')); ?>" method="POST" class="d-inline">
							<?php echo csrf_field(); ?>
							<button type="submit" class="btn btn-info radius-12 text-white h-56-px">
								<iconify-icon icon="solar:refresh-outline"></iconify-icon> Sync WhatsApp Templates
							</button>
						</form>
					<?php endif; ?>
					<a href="<?php echo e(route('admin.marketing.templates.create')); ?>" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add Template
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Name</th>
							<th>Type</th>
							<th>Subject</th>
							<th>WhatsApp Status</th>
							<th>Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium"><?php echo e($template->name); ?></h6>
								</td>
								<td>
									<span class="badge bg-<?php echo e($template->type === 'email' ? 'primary' : ($template->type === 'whatsapp' ? 'success' : 'info')); ?>">
										<?php echo e(ucfirst(str_replace('_', ' ', $template->type))); ?>

									</span>
								</td>
								<td>
									<span class="text-sm"><?php echo e($template->subject ?? '—'); ?></span>
								</td>
								<td>
									<?php if($template->type === 'whatsapp' && $template->whatsapp_template_status): ?>
										<span class="badge bg-<?php echo e($template->whatsapp_template_status === 'approved' ? 'success' : ($template->whatsapp_template_status === 'rejected' ? 'danger' : 'warning')); ?>">
											<?php echo e(ucfirst($template->whatsapp_template_status)); ?>

										</span>
									<?php else: ?>
										<span class="text-secondary-light">—</span>
									<?php endif; ?>
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm <?php echo e($template->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600'); ?>">
										<?php echo e($template->is_active ? 'Active' : 'Inactive'); ?>

									</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.marketing.templates.edit', $template)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="<?php echo e(route('admin.marketing.templates.destroy', $template)); ?>" method="POST" onsubmit="return confirm('Delete this template?')" class="d-inline">
											<?php echo csrf_field(); ?>
											<?php echo method_field('DELETE'); ?>
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="6" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No templates" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No templates found</h6>
									<p class="text-secondary-light mb-0">Create your first marketing template.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($templates->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($templates->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/marketing/templates/index.blade.php ENDPATH**/ ?>