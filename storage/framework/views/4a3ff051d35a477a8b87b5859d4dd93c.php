

<?php $__env->startSection('title', 'Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Users</h6>
			<p class="text-secondary-light mb-0">Manage administrators, staff, and customers.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Users</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>
	<?php if(session('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?php echo e(session('error')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-lg-3 mb-24">
			<div class="card border-0 h-100">
				<div class="card-body p-16">
					<div class="nav flex-column nav-pills gap-2" id="user-tabs" role="tablist" aria-orientation="vertical">
						<a href="<?php echo e(route('admin.users.index', ['tab' => 'admins'])); ?>" class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(($activeTab ?? 'admins') === 'admins' ? 'active' : ''); ?>" id="user-tab-admins" type="button" role="tab">
							<span>Admins</span>
							<iconify-icon icon="solar:shield-user-outline" class="text-lg"></iconify-icon>
						</a>
						<a href="<?php echo e(route('admin.users.index', ['tab' => 'staff'])); ?>" class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(($activeTab ?? '') === 'staff' ? 'active' : ''); ?>" id="user-tab-staff" type="button" role="tab">
							<span>Staff</span>
							<iconify-icon icon="solar:users-group-two-rounded-outline" class="text-lg"></iconify-icon>
						</a>
						<a href="<?php echo e(route('admin.users.index', ['tab' => 'customers'])); ?>" class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(($activeTab ?? '') === 'customers' ? 'active' : ''); ?>" id="user-tab-customers" type="button" role="tab">
							<span>Customers</span>
							<iconify-icon icon="solar:user-id-outline" class="text-lg"></iconify-icon>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="tab-content" id="user-tabs-content">
				<?php if(($activeTab ?? 'admins') === 'admins'): ?>
					<?php echo $__env->make('admin.users.partials.admins', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php elseif(($activeTab ?? '') === 'staff'): ?>
					<?php echo $__env->make('admin.users.partials.staff', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php elseif(($activeTab ?? '') === 'customers'): ?>
					<?php echo $__env->make('admin.users.partials.customers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php else: ?>
					<?php echo $__env->make('admin.users.partials.admins', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/users/index.blade.php ENDPATH**/ ?>