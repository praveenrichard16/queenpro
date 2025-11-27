

<?php $__env->startSection('title', 'Webhook Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Webhook Logs</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">Dashboard</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Webhook Logs</li>
		</ul>
	</div>

	<!-- Statistics Cards -->
	<div class="row g-3 mb-24">
		<div class="col-md-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<p class="text-secondary-light text-sm mb-1">Total Logs</p>
					<h3 class="fw-semibold mb-0"><?php echo e($totalLogs); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<p class="text-secondary-light text-sm mb-1">Successful</p>
					<h3 class="fw-semibold mb-0 text-success"><?php echo e($successful); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<p class="text-secondary-light text-sm mb-1">Failed</p>
					<h3 class="fw-semibold mb-0 text-danger"><?php echo e($failed); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<p class="text-secondary-light text-sm mb-1">Pending</p>
					<h3 class="fw-semibold mb-0 text-warning"><?php echo e($pending); ?></h3>
				</div>
			</div>
		</div>
	</div>

	<!-- Filters -->
	<div class="card border-0 mb-24">
		<div class="card-body p-24">
			<form method="GET" action="<?php echo e(route('admin.api.webhook-logs.index')); ?>" class="row g-3">
				<div class="col-md-4">
					<input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search by event type or IP..." class="form-control bg-neutral-50 radius-12 h-56-px">
				</div>
				<div class="col-md-3">
					<select name="status" class="form-control bg-neutral-50 radius-12 h-56-px">
						<option value="all" <?php echo e($status === 'all' ? 'selected' : ''); ?>>All Status</option>
						<option value="pending" <?php echo e($status === 'pending' ? 'selected' : ''); ?>>Pending</option>
						<option value="successful" <?php echo e($status === 'successful' ? 'selected' : ''); ?>>Successful</option>
						<option value="failed" <?php echo e($status === 'failed' ? 'selected' : ''); ?>>Failed</option>
					</select>
				</div>
				<div class="col-md-3">
					<select name="event_type" class="form-control bg-neutral-50 radius-12 h-56-px">
						<option value="all" <?php echo e($eventType === 'all' ? 'selected' : ''); ?>>All Events</option>
						<option value="lead.created" <?php echo e($eventType === 'lead.created' ? 'selected' : ''); ?>>Lead Created</option>
						<option value="lead.updated" <?php echo e($eventType === 'lead.updated' ? 'selected' : ''); ?>>Lead Updated</option>
						<option value="customer.created" <?php echo e($eventType === 'customer.created' ? 'selected' : ''); ?>>Customer Created</option>
					</select>
				</div>
				<div class="col-md-2">
					<button type="submit" class="btn btn-primary radius-12 h-56-px w-100">
						<iconify-icon icon="solar:filter-outline" class="me-1"></iconify-icon>
						Filter
					</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Logs Table -->
	<div class="card border-0">
		<div class="card-body p-24">
			<h6 class="fw-semibold mb-16">Webhook Activity Log</h6>
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Event Type</th>
							<th>Source</th>
							<th>IP Address</th>
							<th>Status</th>
							<th>Response Code</th>
							<th>Created At</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td><code class="text-sm"><?php echo e($log->event_type); ?></code></td>
								<td><?php echo e($log->source ?? '—'); ?></td>
								<td><?php echo e($log->ip_address ?? '—'); ?></td>
								<td>
									<span class="px-12 py-4 rounded-pill fw-semibold text-xs 
										<?php echo e($log->status === 'successful' ? 'bg-success-focus text-success-main' : 
										   ($log->status === 'failed' ? 'bg-danger-focus text-danger-main' : 'bg-warning-focus text-warning-main')); ?>">
										<?php echo e(ucfirst($log->status)); ?>

									</span>
								</td>
								<td><?php echo e($log->response_code ?? '—'); ?></td>
								<td><?php echo e($log->created_at->format('M d, Y H:i')); ?></td>
								<td class="text-end">
									<button type="button" class="btn btn-sm btn-outline-primary" onclick="viewLogDetails(<?php echo e($log->id); ?>)">
										View
									</button>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="7" class="text-center py-80">
									<p class="text-secondary-light mb-0">No webhook logs found. Webhook activity will appear here once webhooks are received.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($logs->hasPages()): ?>
				<div class="mt-16">
					<?php echo e($logs->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
function viewLogDetails(logId) {
	// TODO: Implement modal to show log details
	alert('Log details view will be implemented');
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/api/webhook-logs/index.blade.php ENDPATH**/ ?>