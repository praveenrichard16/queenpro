

<?php $__env->startSection('title', 'Location Manager'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">India Location Manager</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Settings</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Location Manager</li>
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

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
				<div class="d-flex flex-wrap gap-2">
					<a href="<?php echo e(route('admin.settings.locations.create')); ?>" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add Location
					</a>
					<a href="<?php echo e(route('admin.settings.locations.import')); ?>" class="btn btn-info radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:import-outline"></iconify-icon>
						Import
					</a>
					<a href="<?php echo e(route('admin.settings.locations.export')); ?>" class="btn btn-success radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:export-outline"></iconify-icon>
						Export CSV
					</a>
					<a href="<?php echo e(route('admin.settings.locations.export', ['format' => 'json'])); ?>" class="btn btn-success radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:export-outline"></iconify-icon>
						Export JSON
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" action="<?php echo e(route('admin.settings.locations.index')); ?>" class="row g-3">
				<div class="col-md-2">
					<label class="form-label text-secondary-light">Search</label>
					<input type="text" name="search" class="form-control bg-neutral-50 radius-12 h-56-px" 
						   value="<?php echo e(request('search')); ?>" placeholder="Search...">
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">State</label>
					<select name="region" class="form-control bg-neutral-50 radius-12 h-56-px">
						<option value="">All States</option>
						<?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($region); ?>" <?php echo e(request('region') === $region ? 'selected' : ''); ?>><?php echo e($region); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">City</label>
					<select name="city" class="form-control bg-neutral-50 radius-12 h-56-px">
						<option value="">All Cities</option>
						<?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($city); ?>" <?php echo e(request('city') === $city ? 'selected' : ''); ?>><?php echo e($city); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">District</label>
					<select name="district" class="form-control bg-neutral-50 radius-12 h-56-px">
						<option value="">All Districts</option>
						<?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($district); ?>" <?php echo e(request('district') === $district ? 'selected' : ''); ?>><?php echo e($district); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label text-secondary-light">Pincode</label>
					<input type="text" name="pincode" class="form-control bg-neutral-50 radius-12 h-56-px" 
						   value="<?php echo e(request('pincode')); ?>" placeholder="Enter pincode">
				</div>
				<div class="col-md-1">
					<label class="form-label text-secondary-light">Status</label>
					<select name="status" class="form-control bg-neutral-50 radius-12 h-56-px">
						<option value="">All</option>
						<option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
						<option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
					</select>
				</div>
				<div class="col-md-1 d-flex align-items-end">
					<button type="submit" class="btn btn-primary radius-12 h-56-px w-100">Filter</button>
				</div>
			</form>
			<div class="row mt-3">
				<div class="col-12">
					<a href="<?php echo e(route('admin.settings.locations.index')); ?>" class="btn btn-outline-secondary radius-12 h-56-px d-inline-flex align-items-center gap-2">
						<iconify-icon icon="solar:refresh-outline"></iconify-icon>
						Clear Filters
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
							<th>State</th>
							<th>City</th>
							<th>District</th>
							<th>Postal Code</th>
							<th>Status</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium"><?php echo e($location->region_name_en ?? '—'); ?></h6>
								</td>
								<td>
									<h6 class="text-md mb-0 fw-medium"><?php echo e($location->city_name_en ?? '—'); ?></h6>
								</td>
								<td>
									<span class="text-sm"><?php echo e($location->district_name_en ?? '—'); ?></span>
								</td>
								<td>
									<code class="text-sm"><?php echo e($location->postal_code ?? '—'); ?></code>
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm <?php echo e($location->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600'); ?>">
										<?php echo e($location->is_active ? 'Active' : 'Inactive'); ?>

									</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.settings.locations.edit', $location)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="<?php echo e(route('admin.settings.locations.destroy', $location)); ?>" method="POST" onsubmit="return confirm('Delete this location?')" class="d-inline">
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
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No locations" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No locations found</h6>
									<p class="text-secondary-light mb-0">Create your first location or import locations from a file.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($locations->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($locations->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/settings/locations/index.blade.php ENDPATH**/ ?>