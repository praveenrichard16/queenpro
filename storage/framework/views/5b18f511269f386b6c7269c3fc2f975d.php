

<?php $__env->startSection('title', $title . ' - Documentation'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0"><?php echo e($title); ?></h6>
			<p class="text-secondary-light mb-0">Setup guide and documentation</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.documentation.index')); ?>" class="hover-text-primary">Documentation</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light"><?php echo e($title); ?></li>
		</ul>
	</div>

	<div class="card border-0 bg-base">
		<div class="card-body p-24">
			<div class="documentation-content">
				<?php echo $html; ?>

			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
	.documentation-content {
		max-width: 100%;
		line-height: 1.8;
		color: #333;
	}
	
	.documentation-content h1 {
		font-size: 2rem;
		font-weight: 700;
		margin-bottom: 1.5rem;
		margin-top: 2rem;
		color: #024550;
		border-bottom: 2px solid #024550;
		padding-bottom: 0.5rem;
	}
	
	.documentation-content h1:first-child {
		margin-top: 0;
	}
	
	.documentation-content h2 {
		font-size: 1.5rem;
		font-weight: 600;
		margin-top: 2rem;
		margin-bottom: 1rem;
		color: #024550;
	}
	
	.documentation-content h3 {
		font-size: 1.25rem;
		font-weight: 600;
		margin-top: 1.5rem;
		margin-bottom: 0.75rem;
		color: #03606a;
	}
	
	.documentation-content h4,
	.documentation-content h5,
	.documentation-content h6 {
		font-weight: 600;
		margin-top: 1.25rem;
		margin-bottom: 0.5rem;
		color: #03606a;
	}
	
	.documentation-content p {
		margin-bottom: 1rem;
		text-align: justify;
	}
	
	.documentation-content ul,
	.documentation-content ol {
		margin-bottom: 1rem;
		padding-left: 2rem;
	}
	
	.documentation-content li {
		margin-bottom: 0.5rem;
	}
	
	.documentation-content code {
		background-color: #f4f4f4;
		padding: 0.2rem 0.4rem;
		border-radius: 0.25rem;
		font-family: 'Courier New', monospace;
		font-size: 0.9em;
		color: #d63384;
	}
	
	.documentation-content pre {
		background-color: #f8f9fa;
		border: 1px solid #dee2e6;
		border-radius: 0.5rem;
		padding: 1rem;
		overflow-x: auto;
		margin-bottom: 1rem;
	}
	
	.documentation-content pre code {
		background-color: transparent;
		padding: 0;
		color: #333;
		font-size: 0.9rem;
	}
	
	.documentation-content a {
		color: #024550;
		text-decoration: underline;
	}
	
	.documentation-content a:hover {
		color: #03606a;
	}
	
	.documentation-content strong {
		font-weight: 600;
		color: #024550;
	}
	
	.documentation-content em {
		font-style: italic;
	}
</style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/documentation/show.blade.php ENDPATH**/ ?>