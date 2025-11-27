

<?php $__env->startSection('title', 'Webhook Configuration'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Webhook Configuration</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Webhook Configuration</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<!-- Statistics Cards -->
	<div class="row g-3 mb-24">
		<div class="col-md-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<p class="text-secondary-light text-sm mb-1">Total Webhooks</p>
					<h3 class="fw-semibold mb-0"><?php echo e($totalWebhooks); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<p class="text-secondary-light text-sm mb-1">Last 24 Hours</p>
					<h3 class="fw-semibold mb-0"><?php echo e($recentWebhooks); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<p class="text-secondary-light text-sm mb-1">Successful</p>
					<h3 class="fw-semibold mb-0 text-success"><?php echo e($successful ?? 0); ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<p class="text-secondary-light text-sm mb-1">Failed</p>
					<h3 class="fw-semibold mb-0 text-danger"><?php echo e($failed ?? 0); ?></h3>
				</div>
			</div>
		</div>
	</div>

	<!-- Webhook Endpoint URL -->
	<div class="card border-0 mb-24">
		<div class="card-body p-24">
			<h6 class="fw-semibold mb-12">Webhook Endpoint URL</h6>
			<div class="d-flex gap-2 mb-3">
				<input type="text" class="form-control bg-neutral-50 radius-12" value="<?php echo e(url('/webhooks')); ?>" readonly>
				<button type="button" class="btn btn-primary radius-12" onclick="copyToClipboard('<?php echo e(url('/webhooks')); ?>')">
					<iconify-icon icon="solar:copy-outline" class="me-1"></iconify-icon>
					Copy
				</button>
			</div>
			<p class="text-secondary-light text-sm mb-0">Use this generic URL for all webhook sources. Configure endpoints below to manage different sources.</p>
			<div class="alert alert-info mt-12">
				<iconify-icon icon="solar:info-circle-bold"></iconify-icon>
				<strong>How it works:</strong> All webhooks are sent to the same URL. The system automatically detects the source from headers or payload and routes to the appropriate configured endpoint.
			</div>
		</div>
	</div>

	<!-- Configured Endpoints -->
	<div class="card border-0">
		<div class="card-body p-24">
			<div class="d-flex justify-content-between align-items-center mb-16">
				<h6 class="fw-semibold mb-0">Configured Webhook Endpoints</h6>
				<a href="<?php echo e(route('admin.api.webhooks.create')); ?>" class="btn btn-primary radius-12">
					<iconify-icon icon="solar:add-circle-outline" class="me-1"></iconify-icon>
					Add Endpoint
				</a>
			</div>
			
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Name</th>
							<th>Source</th>
							<th>Secret</th>
							<th>Status</th>
							<th>Created</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $endpoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $endpoint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium"><?php echo e($endpoint->name); ?></h6>
									<p class="text-xs text-secondary-light mb-0"><?php echo e(Str::limit($endpoint->url, 50)); ?></p>
								</td>
								<td>
									<span class="text-sm"><?php echo e($endpoint->source ?? 'All'); ?></span>
								</td>
								<td>
									<span class="text-sm"><?php echo e($endpoint->secret ? '••••••••' : '—'); ?></span>
								</td>
								<td>
									<span class="px-12 py-4 rounded-pill fw-semibold text-xs <?php echo e($endpoint->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600'); ?>">
										<?php echo e($endpoint->is_active ? 'Active' : 'Inactive'); ?>

									</span>
								</td>
								<td>
									<span class="text-sm"><?php echo e($endpoint->created_at->format('M d, Y')); ?></span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.api.webhooks.edit', $endpoint)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="<?php echo e(route('admin.api.webhooks.destroy', $endpoint)); ?>" method="POST" onsubmit="return confirm('Delete this webhook endpoint?')" class="d-inline">
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
									<p class="text-secondary-light mb-2">No webhook endpoints configured.</p>
									<a href="<?php echo e(route('admin.api.webhooks.create')); ?>" class="btn btn-primary btn-sm">Add Endpoint</a>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($endpoints->hasPages()): ?>
				<div class="mt-16">
					<?php echo e($endpoints->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- Supported Events -->
	<div class="card border-0 mt-3">
		<div class="card-body p-24">
			<h6 class="fw-semibold mb-16">Supported Webhook Events</h6>
			<div class="row g-3">
				<div class="col-md-4">
					<h6 class="fw-semibold mb-2">Lead Events</h6>
					<ul class="mb-0">
						<li><code>lead.created</code> - When a new lead is created</li>
						<li><code>lead.updated</code> - When a lead is updated</li>
					</ul>
				</div>
				<div class="col-md-4">
					<h6 class="fw-semibold mb-2">Customer Events</h6>
					<ul class="mb-0">
						<li><code>customer.created</code> - When a new customer is created</li>
						<li><code>customer.updated</code> - When a customer is updated</li>
					</ul>
				</div>
				<div class="col-md-4">
					<h6 class="fw-semibold mb-2">Message Events</h6>
					<ul class="mb-0">
						<li><code>message.received</code> - When a message is received</li>
					</ul>
				</div>
			</div>
			<div class="mt-16">
				<a href="<?php echo e(route('admin.api.webhook-logs.index')); ?>" class="btn btn-primary radius-12">
					<iconify-icon icon="solar:document-text-outline" class="me-1"></iconify-icon>
					View Webhook Logs
				</a>
			</div>
		</div>
	</div>
</div>

<script>
function copyToClipboard(text) {
	navigator.clipboard.writeText(text).then(function() {
		alert('Copied to clipboard!');
	}, function() {
		const textArea = document.createElement('textarea');
		textArea.value = text;
		document.body.appendChild(textArea);
		textArea.select();
		document.execCommand('copy');
		document.body.removeChild(textArea);
		alert('Copied to clipboard!');
	});
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/api/webhooks/index.blade.php ENDPATH**/ ?>