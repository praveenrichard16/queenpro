

<?php $__env->startSection('title', 'Customers'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Customers</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Customers</li>
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
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name, email, phone">
					</div>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Customer Type</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="type">
						<option value="">All customers</option>
						<option value="paid" <?php if(request('type') === 'paid'): echo 'selected'; endif; ?>>Paid Customers</option>
						<option value="cart_abandoners" <?php if(request('type') === 'cart_abandoners'): echo 'selected'; endif; ?>>Cart Abandoners</option>
						<option value="repeat" <?php if(request('type') === 'repeat'): echo 'selected'; endif; ?>>Repeat Customers</option>
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-warning radius-12 text-white w-100 h-56-px d-inline-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#bulkMessagingModal">
						<iconify-icon icon="solar:chat-round-call-outline"></iconify-icon>
						Bulk Message
					</button>
				</div>
			</form>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>
								<input type="checkbox" id="selectAll" class="form-check-input">
							</th>
							<th>Customer</th>
							<th>Contact</th>
							<th>Orders</th>
							<th>Total Spent</th>
							<th>Joined</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<input type="checkbox" name="customer_ids[]" value="<?php echo e($customer->id); ?>" class="form-check-input customer-checkbox">
								</td>
								<td>
									<div class="d-flex align-items-center gap-3">
										<div class="w-40-px h-40-px rounded-circle bg-primary-focus d-flex align-items-center justify-content-center">
											<span class="text-primary-main fw-semibold"><?php echo e(substr($customer->name, 0, 1)); ?></span>
										</div>
										<div>
											<h6 class="text-md mb-1 fw-medium"><?php echo e($customer->name); ?></h6>
											<p class="text-xs text-secondary-light mb-0"><?php echo e($customer->email); ?></p>
										</div>
									</div>
								</td>
								<td>
									<?php if($customer->phone): ?>
										<span class="text-sm"><?php echo e($customer->phone); ?></span>
									<?php else: ?>
										<span class="text-secondary-light">—</span>
									<?php endif; ?>
								</td>
								<td>
									<span class="fw-semibold"><?php echo e($customer->orders_count ?? 0); ?></span>
								</td>
								<td>
									<span class="fw-semibold"><?php echo e(\App\Services\CurrencyService::format($customer->orders_sum_total_amount ?? 0)); ?></span>
								</td>
								<td>
									<span class="text-sm text-secondary-light"><?php echo e($customer->created_at->format('M d, Y')); ?></span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.customers.show', $customer)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center" title="View">
											<iconify-icon icon="solar:eye-bold"></iconify-icon>
										</a>
										<a href="<?php echo e(route('admin.customers.journey', $customer)); ?>" class="w-36-px h-36-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Journey">
											<iconify-icon icon="solar:route-outline"></iconify-icon>
										</a>
									</div>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="7" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No customers" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No customers found</h6>
									<p class="text-secondary-light mb-0">Customer records will appear here.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($customers->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($customers->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- Bulk Messaging Modal -->
<div class="modal fade" id="bulkMessagingModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Bulk Messaging</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<form action="<?php echo e(route('admin.customers.bulk-messaging')); ?>" method="POST">
				<?php echo csrf_field(); ?>
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label">Message Type</label>
						<select name="message_type" class="form-select" required>
							<option value="email">Email</option>
							<option value="whatsapp">WhatsApp</option>
							<option value="push">Push Notification</option>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label">Template (Optional)</label>
						<select name="template_id" class="form-select">
							<option value="">Select template</option>
							<!-- Templates will be loaded here -->
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label">Subject</label>
						<input type="text" name="subject" class="form-control" placeholder="Message subject">
					</div>
					<div class="mb-3">
						<label class="form-label">Message</label>
						<textarea name="message" class="form-control" rows="5" required placeholder="Enter your message"></textarea>
					</div>
					<input type="hidden" name="customer_ids" id="selectedCustomerIds">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Send Messages</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
	const checkboxes = document.querySelectorAll('.customer-checkbox');
	checkboxes.forEach(cb => cb.checked = this.checked);
});

document.getElementById('bulkMessagingModal')?.addEventListener('show.bs.modal', function() {
	const selected = Array.from(document.querySelectorAll('.customer-checkbox:checked')).map(cb => cb.value);
	document.getElementById('selectedCustomerIds').value = selected.join(',');
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>