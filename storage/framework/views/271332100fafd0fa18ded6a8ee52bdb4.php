

<?php $__env->startSection('title', 'API Keys'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">API Keys</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">API Keys</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<?php if(session('token_plain')): ?>
				<div class="mt-3 p-3 bg-white rounded border">
					<p class="mb-2 fw-semibold">Token: <code class="text-break"><?php echo e(session('token_plain')); ?></code></p>
					<p class="mb-0 text-sm text-danger">⚠️ Please copy this token now. It will not be shown again.</p>
					<button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="copyToken('<?php echo e(session('token_plain')); ?>')">
						Copy Token
					</button>
				</div>
			<?php endif; ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>
	<?php if(session('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?php echo e(session('error')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<!-- Statistics Cards -->
	<div class="row g-3 mb-24">
		<div class="col-md-6">
			<div class="card border-0">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between">
						<div>
							<p class="text-secondary-light text-sm mb-1">Total Tokens</p>
							<h3 class="fw-semibold mb-0"><?php echo e($totalTokens); ?></h3>
						</div>
						<div class="w-56-px h-56-px bg-primary-focus rounded-circle d-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:key-outline" class="text-primary-main text-xl"></iconify-icon>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card border-0">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between">
						<div>
							<p class="text-secondary-light text-sm mb-1">Active Tokens</p>
							<h3 class="fw-semibold mb-0"><?php echo e($activeTokens); ?></h3>
						</div>
						<div class="w-56-px h-56-px bg-success-focus rounded-circle d-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:check-circle-bold" class="text-success-main text-xl"></iconify-icon>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
				<form method="GET" action="<?php echo e(route('admin.api-tokens.index')); ?>" class="d-flex gap-2 flex-grow-1" style="max-width: 400px;">
					<input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search by name, user name or email..." class="form-control bg-neutral-50 radius-12 h-56-px">
					<button type="submit" class="btn btn-primary radius-12 px-24 h-56-px">Search</button>
					<?php if($search): ?>
						<a href="<?php echo e(route('admin.api-tokens.index')); ?>" class="btn btn-outline-secondary radius-12 px-24 h-56-px">Clear</a>
					<?php endif; ?>
				</form>
				<a href="<?php echo e(route('admin.api-tokens.create')); ?>" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
					<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					Create API Key
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
							<th>Name</th>
							<th>User</th>
							<th>Abilities</th>
							<th>Last Used</th>
							<th>Created</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $tokens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $token): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium"><?php echo e($token->name); ?></h6>
								</td>
								<td>
									<?php if($token->tokenable): ?>
										<div>
											<span class="fw-semibold"><?php echo e($token->tokenable->name); ?></span>
											<span class="text-sm text-secondary-light d-block"><?php echo e($token->tokenable->email); ?></span>
										</div>
									<?php else: ?>
										<span class="text-secondary-light">—</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if(is_array($token->abilities) && count($token->abilities) > 0): ?>
										<div class="d-flex flex-wrap gap-1">
											<?php $__currentLoopData = $token->abilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<span class="px-12 py-4 rounded-pill fw-semibold text-xs bg-primary-focus text-primary-main">
													<?php echo e($ability); ?>

												</span>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</div>
									<?php else: ?>
										<span class="px-12 py-4 rounded-pill fw-semibold text-xs bg-success-focus text-success-main">
											All
										</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if($token->last_used_at): ?>
										<span class="text-sm"><?php echo e($token->last_used_at->diffForHumans()); ?></span>
									<?php else: ?>
										<span class="text-secondary-light">Never</span>
									<?php endif; ?>
								</td>
								<td>
									<span class="text-sm"><?php echo e($token->created_at->format('M d, Y')); ?></span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.api-tokens.edit', $token)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="<?php echo e(route('admin.api-tokens.destroy', $token)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this API token? This action cannot be undone.')" class="d-inline">
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
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No API tokens" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No API tokens found</h6>
									<p class="text-secondary-light mb-0">Create your first API token to enable API access.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($tokens->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($tokens->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
function copyToken(token) {
	navigator.clipboard.writeText(token).then(function() {
		alert('Token copied to clipboard!');
	}, function() {
		// Fallback for older browsers
		const textArea = document.createElement('textarea');
		textArea.value = token;
		document.body.appendChild(textArea);
		textArea.select();
		document.execCommand('copy');
		document.body.removeChild(textArea);
		alert('Token copied to clipboard!');
	});
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/api-tokens/index.blade.php ENDPATH**/ ?>