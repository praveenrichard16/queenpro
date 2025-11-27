

<?php $__env->startSection('title', 'Trigger Drip Campaign'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Trigger Drip Campaign</h6>
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
			<li class="fw-medium text-secondary-light">Trigger</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-lg-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Select Campaign</h6>
					<form method="GET" id="campaignForm">
						<input type="hidden" name="recipient_type" value="<?php echo e($recipientType ?? 'customer'); ?>">
						<input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
						<select name="campaign_id" class="form-select bg-neutral-50 radius-12" onchange="document.getElementById('campaignForm').submit()">
							<option value="">Select Campaign</option>
							<?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($campaign->id); ?>" <?php if(request('campaign_id') == $campaign->id): echo 'selected'; endif; ?>>
									<?php echo e($campaign->name); ?>

								</option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</form>
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<?php if(request('campaign_id')): ?>
						<!-- Recipient Type Tabs -->
						<ul class="nav nav-pills mb-3" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php echo e(($recipientType ?? 'customer') === 'customer' ? 'active' : ''); ?>" 
										type="button" 
										onclick="window.location.href='<?php echo e(route('admin.marketing.drip-campaigns.trigger', ['campaign_id' => request('campaign_id'), 'recipient_type' => 'customer'])); ?>'">
									Customers
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php echo e(($recipientType ?? '') === 'enquiry' ? 'active' : ''); ?>" 
										type="button"
										onclick="window.location.href='<?php echo e(route('admin.marketing.drip-campaigns.trigger', ['campaign_id' => request('campaign_id'), 'recipient_type' => 'enquiry'])); ?>'">
									Enquiries
								</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link <?php echo e(($recipientType ?? '') === 'lead' ? 'active' : ''); ?>" 
										type="button"
										onclick="window.location.href='<?php echo e(route('admin.marketing.drip-campaigns.trigger', ['campaign_id' => request('campaign_id'), 'recipient_type' => 'lead'])); ?>'">
									Leads
								</button>
							</li>
						</ul>

						<form method="GET" class="mb-3">
							<input type="hidden" name="campaign_id" value="<?php echo e(request('campaign_id')); ?>">
							<input type="hidden" name="recipient_type" value="<?php echo e($recipientType ?? 'customer'); ?>">
							<div class="icon-field">
								<span class="icon top-50 translate-middle-y">
									<iconify-icon icon="mage:search"></iconify-icon>
								</span>
								<input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Search <?php echo e($recipientType ?? 'customers'); ?>...">
							</div>
						</form>

						<form method="POST" action="<?php echo e(route('admin.marketing.drip-campaigns.trigger-campaign')); ?>" id="triggerForm">
							<?php echo csrf_field(); ?>
							<input type="hidden" name="campaign_id" value="<?php echo e(request('campaign_id')); ?>">
							<input type="hidden" name="recipient_type" value="<?php echo e($recipientType ?? 'customer'); ?>">

							<?php if(($recipientType ?? 'customer') === 'customer'): ?>
								<div class="table-responsive">
									<table class="table bordered-table mb-0">
										<thead>
											<tr>
												<th width="50">
													<input type="checkbox" id="selectAll" class="form-check-input">
												</th>
												<th>Customer</th>
												<th>Email</th>
												<th>Phone</th>
											</tr>
										</thead>
										<tbody>
											<?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
												<tr>
													<td>
														<input type="checkbox" name="recipient_ids[]" value="<?php echo e($customer->id); ?>" class="form-check-input recipient-checkbox">
													</td>
													<td><?php echo e($customer->name); ?></td>
													<td><?php echo e($customer->email); ?></td>
													<td><?php echo e($customer->phone ?? '-'); ?></td>
												</tr>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
												<tr>
													<td colspan="4" class="text-center py-4 text-secondary-light">No customers found.</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<?php if($customers->hasPages()): ?>
									<div class="mt-3">
										<?php echo e($customers->links()); ?>

									</div>
								<?php endif; ?>
							<?php elseif(($recipientType ?? '') === 'enquiry'): ?>
								<div class="table-responsive">
									<table class="table bordered-table mb-0">
										<thead>
											<tr>
												<th width="50">
													<input type="checkbox" id="selectAll" class="form-check-input">
												</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Product</th>
											</tr>
										</thead>
										<tbody>
											<?php $__empty_1 = true; $__currentLoopData = $enquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
												<tr>
													<td>
														<input type="checkbox" name="recipient_ids[]" value="<?php echo e($enquiry->id); ?>" class="form-check-input recipient-checkbox">
													</td>
													<td><?php echo e($enquiry->customer_name); ?></td>
													<td><?php echo e($enquiry->customer_email); ?></td>
													<td><?php echo e($enquiry->customer_phone ?? '-'); ?></td>
													<td><?php echo e($enquiry->product->name ?? '-'); ?></td>
												</tr>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
												<tr>
													<td colspan="5" class="text-center py-4 text-secondary-light">No enquiries found.</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<?php if($enquiries->hasPages()): ?>
									<div class="mt-3">
										<?php echo e($enquiries->links()); ?>

									</div>
								<?php endif; ?>
							<?php elseif(($recipientType ?? '') === 'lead'): ?>
								<div class="table-responsive">
									<table class="table bordered-table mb-0">
										<thead>
											<tr>
												<th width="50">
													<input type="checkbox" id="selectAll" class="form-check-input">
												</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
												<tr>
													<td>
														<input type="checkbox" name="recipient_ids[]" value="<?php echo e($lead->id); ?>" class="form-check-input recipient-checkbox">
													</td>
													<td><?php echo e($lead->name); ?></td>
													<td><?php echo e($lead->email); ?></td>
													<td><?php echo e($lead->phone ?? '-'); ?></td>
													<td>
														<span class="badge bg-info"><?php echo e($lead->stage->name ?? '-'); ?></span>
													</td>
												</tr>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
												<tr>
													<td colspan="5" class="text-center py-4 text-secondary-light">No leads found.</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<?php if($leads->hasPages()): ?>
									<div class="mt-3">
										<?php echo e($leads->links()); ?>

									</div>
								<?php endif; ?>
							<?php endif; ?>

							<div class="mt-3 d-flex align-items-center justify-content-between">
								<div>
									<strong>Selected: <span id="selectedCount">0</span></strong>
								</div>
								<button type="submit" class="btn btn-primary">
									Trigger Campaign for Selected <?php echo e(ucfirst($recipientType ?? 'customers')); ?>

								</button>
							</div>
						</form>
					<?php else: ?>
						<p class="text-center py-4 text-secondary-light">Please select a campaign to trigger.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
	document.getElementById('selectAll')?.addEventListener('change', function() {
		const checkboxes = document.querySelectorAll('.recipient-checkbox');
		checkboxes.forEach(checkbox => {
			checkbox.checked = this.checked;
		});
		updateSelectedCount();
	});

	document.querySelectorAll('.recipient-checkbox').forEach(checkbox => {
		checkbox.addEventListener('change', function() {
			const allCheckboxes = document.querySelectorAll('.recipient-checkbox');
			const checkedCheckboxes = document.querySelectorAll('.recipient-checkbox:checked');
			const selectAll = document.getElementById('selectAll');
			
			if (selectAll) {
				selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
			}
			updateSelectedCount();
		});
	});

	function updateSelectedCount() {
		const checked = document.querySelectorAll('.recipient-checkbox:checked');
		const countElement = document.getElementById('selectedCount');
		if (countElement) {
			countElement.textContent = checked.length;
		}
	}

	document.getElementById('triggerForm')?.addEventListener('submit', function(e) {
		const checked = document.querySelectorAll('.recipient-checkbox:checked');
		if (checked.length === 0) {
			e.preventDefault();
			alert('Please select at least one recipient.');
			return false;
		}
	});

	// Initialize count on page load
	updateSelectedCount();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/marketing/drip-campaigns/trigger.blade.php ENDPATH**/ ?>