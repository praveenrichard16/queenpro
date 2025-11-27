

<?php $__env->startSection('title', 'API Documentation'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex justify-content-between align-items-center mb-24">
		<h6 class="fw-semibold mb-0">API Documentation</h6>
	</div>

	<div class="card border-0 mb-24">
		<div class="card-body p-24">
			<h5 class="fw-semibold mb-12">Base URL</h5>
			<p class="mb-0">
				<code><?php echo e(url('/api/v1')); ?></code>
			</p>
		</div>
	</div>

	<div class="card border-0 mb-24">
		<div class="card-body p-24">
			<h5 class="fw-semibold mb-16">Authentication</h5>
			<p class="mb-8">Use an API key created in the <strong>API Access → API Keys</strong> section.</p>
			<p class="mb-0">Send it as a Bearer token in the <code>Authorization</code> header:</p>
			<pre class="mt-2 mb-0"><code>Authorization: Bearer YOUR_API_TOKEN</code></pre>
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<h5 class="fw-semibold mb-16">Key Endpoints (v1)</h5>

			<h6 class="fw-semibold mt-12">Products</h6>
			<ul class="mb-12">
				<li><code>GET /api/v1/products</code> – list products (supports <code>?search=</code>).</li>
				<li><code>GET /api/v1/products/{id}</code> – product details.</li>
			</ul>

			<h6 class="fw-semibold mt-12">Orders</h6>
			<ul class="mb-12">
				<li><code>GET /api/v1/orders</code> – list orders for the token's user.</li>
				<li><code>GET /api/v1/orders/{id}</code> – order details.</li>
				<li><code>POST /api/v1/orders</code> – create order (see schema in code).</li>
			</ul>

			<h6 class="fw-semibold mt-12">Leads &amp; Enquiries</h6>
			<ul class="mb-12">
				<li><code>GET /api/v1/leads</code>, <code>POST /api/v1/leads</code>, etc. – manage leads.</li>
				<li><code>GET /api/v1/enquiries</code>, <code>POST /api/v1/enquiries</code> – manage enquiries.</li>
			</ul>

			<p class="mb-0 text-secondary-light">
				For full schemas and additional endpoints, please refer to the project README or API source controllers
				under <code>app/Http/Controllers/Api/V1</code>.
			</p>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/api/docs.blade.php ENDPATH**/ ?>