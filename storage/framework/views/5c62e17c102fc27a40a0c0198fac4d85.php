

<?php $__env->startSection('title', 'Affiliate Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Affiliate Dashboard</h6>
			<p class="text-secondary-light mb-0">Track your referrals and earnings</p>
		</div>
		<?php if($affiliate->status === 'pending'): ?>
			<span class="badge bg-warning">Pending Approval</span>
		<?php elseif($affiliate->status === 'suspended'): ?>
			<span class="badge bg-danger">Suspended</span>
		<?php endif; ?>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<?php if($affiliate->status !== 'active'): ?>
		<div class="alert alert-warning">
			<strong>Account Status: <?php echo e(ucfirst($affiliate->status)); ?></strong>
			<?php if($affiliate->status === 'pending'): ?>
				<p class="mb-0 mt-2">Your affiliate account is pending approval. You'll be able to start earning commissions once approved.</p>
			<?php else: ?>
				<p class="mb-0 mt-2">Your affiliate account is currently suspended. Please contact support for more information.</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="row g-3 mb-24">
		<div class="col-xl-3 col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-20">
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-secondary-light text-sm">Total Earnings</span>
						<iconify-icon icon="solar:wallet-bold" class="text-2xl text-success"></iconify-icon>
					</div>
					<h3 class="mt-16 mb-0"><?php echo e(\App\Services\CurrencyService::format($stats['total_commissions'])); ?></h3>
					<p class="text-secondary-light text-xs mb-0">All time earnings</p>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-20">
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-secondary-light text-sm">Pending</span>
						<iconify-icon icon="solar:clock-circle-outline" class="text-2xl text-warning"></iconify-icon>
					</div>
					<h3 class="mt-16 mb-0"><?php echo e(\App\Services\CurrencyService::format($stats['pending_commissions'])); ?></h3>
					<p class="text-secondary-light text-xs mb-0">Awaiting approval</p>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-20">
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-secondary-light text-sm">Approved</span>
						<iconify-icon icon="solar:check-circle-outline" class="text-2xl text-info"></iconify-icon>
					</div>
					<h3 class="mt-16 mb-0"><?php echo e(\App\Services\CurrencyService::format($stats['approved_commissions'])); ?></h3>
					<p class="text-secondary-light text-xs mb-0">Ready for payout</p>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-body p-20">
					<div class="d-flex align-items-center justify-content-between">
						<span class="text-secondary-light text-sm">Paid</span>
						<iconify-icon icon="solar:check-square-outline" class="text-2xl text-primary"></iconify-icon>
					</div>
					<h3 class="mt-16 mb-0"><?php echo e(\App\Services\CurrencyService::format($stats['paid_commissions'])); ?></h3>
					<p class="text-secondary-light text-xs mb-0">Total paid out</p>
				</div>
			</div>
		</div>
	</div>

	<?php if($affiliate->status === 'active'): ?>
		<div class="row g-4 mb-24">
			<div class="col-12">
				<div class="card border-0 shadow-sm">
					<div class="card-body p-24">
						<h6 class="fw-semibold mb-20">Earnings Over Time</h6>
						<canvas id="earningsChart" height="80"></canvas>
					</div>
				</div>
			</div>
		</div>

		<div class="row g-4 mb-24">
			<div class="col-md-6">
				<div class="card border-0 shadow-sm">
					<div class="card-body p-24">
						<h6 class="fw-semibold mb-20">Conversion Rate</h6>
						<div class="text-center">
							<div class="mb-3">
								<canvas id="conversionChart" width="200" height="200"></canvas>
							</div>
							<h4 class="fw-semibold mb-0"><?php echo e($analytics['conversion_rate']); ?>%</h4>
							<p class="text-secondary-light small mb-0"><?php echo e($stats['confirmed_referrals']); ?> of <?php echo e($stats['total_referrals']); ?> referrals converted</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card border-0 shadow-sm">
					<div class="card-body p-24">
						<h6 class="fw-semibold mb-20">Performance Metrics</h6>
						<div class="d-flex flex-column gap-3">
							<div>
								<p class="text-secondary-light small mb-1">Average Commission per Order</p>
								<p class="fw-semibold mb-0"><?php echo e(\App\Services\CurrencyService::format($analytics['average_commission'])); ?></p>
							</div>
							<div>
								<p class="text-secondary-light small mb-1">Total Orders</p>
								<p class="fw-semibold mb-0"><?php echo e($stats['total_referrals']); ?></p>
							</div>
							<?php if(!empty($analytics['top_months'])): ?>
								<div>
									<p class="text-secondary-light small mb-2">Top Performing Month</p>
									<p class="fw-semibold mb-0"><?php echo e($analytics['top_months'][0]['month'] ?? 'N/A'); ?></p>
									<p class="text-secondary-light small mb-0"><?php echo e(\App\Services\CurrencyService::format($analytics['top_months'][0]['commissions'] ?? 0)); ?></p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0 shadow-sm">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-20">Your Referral Link</h6>
					<div class="d-flex gap-2 mb-3">
						<input type="text" id="referralLink" value="<?php echo e($affiliate->referral_url); ?>" class="form-control bg-neutral-50 radius-12" readonly>
						<button onclick="copyReferralLink()" class="btn btn-primary radius-12 px-24">Copy</button>
					</div>
					<p class="text-secondary-light small mb-0">Share this link with your audience. You'll earn <?php echo e($affiliate->commission_rate); ?>% commission on every order placed through your link.</p>
				</div>
			</div>

			<div class="card border-0 shadow-sm mt-4">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-20">Recent Commissions</h6>
					<?php if($recentCommissions->isEmpty()): ?>
						<div class="text-center py-32 text-secondary-light">
							No commissions yet. Start sharing your referral link!
						</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="table table-sm">
								<thead>
									<tr>
										<th>Order</th>
										<th class="text-end">Amount</th>
										<th>Status</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									<?php $__currentLoopData = $recentCommissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<tr>
											<td><?php echo e($commission->order->order_number); ?></td>
											<td class="text-end fw-semibold"><?php echo e(\App\Services\CurrencyService::format($commission->amount)); ?></td>
											<td>
												<span class="badge 
													<?php echo e($commission->status === 'paid' ? 'bg-success' : 
													   ($commission->status === 'approved' ? 'bg-info' : 
													   ($commission->status === 'pending' ? 'bg-warning' : 'bg-danger'))); ?>">
													<?php echo e(ucfirst($commission->status)); ?>

												</span>
											</td>
											<td><?php echo e($commission->created_at->format('M d, Y')); ?></td>
										</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0 shadow-sm">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-20">Statistics</h6>
					<div class="d-flex flex-column gap-3">
						<div>
							<p class="text-secondary-light small mb-1">Total Referrals</p>
							<p class="fw-semibold mb-0"><?php echo e($stats['total_referrals']); ?></p>
						</div>
						<div>
							<p class="text-secondary-light small mb-1">Confirmed Referrals</p>
							<p class="fw-semibold mb-0"><?php echo e($stats['confirmed_referrals']); ?></p>
						</div>
						<div>
							<p class="text-secondary-light small mb-1">Commission Rate</p>
							<p class="fw-semibold mb-0"><?php echo e($affiliate->commission_rate); ?>%</p>
						</div>
						<div>
							<p class="text-secondary-light small mb-1">Affiliate Code</p>
							<p class="fw-semibold mb-0"><code><?php echo e($affiliate->affiliate_code); ?></code></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if($affiliate->status === 'active'): ?>
		<div class="row g-4 mb-24">
			<div class="col-lg-8">
				<div class="card border-0 shadow-sm">
					<div class="card-body p-24">
						<h6 class="fw-semibold mb-20">Request Payout</h6>
						
						<?php if($availableForPayout >= $minPayoutThreshold): ?>
							<div class="mb-3">
								<p class="text-secondary-light small mb-2">Available for Payout</p>
								<h4 class="fw-semibold text-success mb-0"><?php echo e(\App\Services\CurrencyService::format($availableForPayout)); ?></h4>
								<p class="text-secondary-light small mb-0 mt-1">Minimum payout: <?php echo e(\App\Services\CurrencyService::format($minPayoutThreshold)); ?></p>
							</div>

							<form method="POST" action="<?php echo e(route('customer.affiliates.payout-request')); ?>" class="mt-4">
								<?php echo csrf_field(); ?>
								<div class="mb-3">
									<label class="form-label text-secondary-light">Amount</label>
									<input type="number" name="amount" step="0.01" min="<?php echo e($minPayoutThreshold); ?>" max="<?php echo e($availableForPayout); ?>" 
										class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										value="<?php echo e(old('amount', min($availableForPayout, $minPayoutThreshold))); ?>" required>
									<?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<div class="invalid-feedback d-block"><?php echo e($message); ?></div>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>
								<div class="mb-3">
									<label class="form-label text-secondary-light">Payment Method</label>
									<select name="payment_method" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
										<option value="">Select payment method</option>
										<option value="bank_transfer" <?php echo e(old('payment_method') === 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
										<option value="paypal" <?php echo e(old('payment_method') === 'paypal' ? 'selected' : ''); ?>>PayPal</option>
										<option value="stripe" <?php echo e(old('payment_method') === 'stripe' ? 'selected' : ''); ?>>Stripe</option>
										<option value="other" <?php echo e(old('payment_method') === 'other' ? 'selected' : ''); ?>>Other</option>
									</select>
									<?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<div class="invalid-feedback d-block"><?php echo e($message); ?></div>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>
								<div class="mb-3">
									<label class="form-label text-secondary-light">Payment Details (Account number, email, etc.)</label>
									<textarea name="payment_details" rows="3" 
										class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['payment_details'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
										placeholder="Enter your payment account details"><?php echo e(old('payment_details')); ?></textarea>
									<?php $__errorArgs = ['payment_details'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
										<div class="invalid-feedback d-block"><?php echo e($message); ?></div>
									<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>
								<button type="submit" class="btn btn-primary radius-12 px-24">Request Payout</button>
							</form>
						<?php else: ?>
							<div class="alert alert-info">
								<p class="mb-2"><strong>Available for Payout:</strong> <?php echo e(\App\Services\CurrencyService::format($availableForPayout)); ?></p>
								<p class="mb-0">You need at least <?php echo e(\App\Services\CurrencyService::format($minPayoutThreshold)); ?> in approved commissions to request a payout.</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="card border-0 shadow-sm">
					<div class="card-body p-24">
						<h6 class="fw-semibold mb-20">Payout History</h6>
						<?php if($payouts->isEmpty()): ?>
							<div class="text-center py-32 text-secondary-light">
								<iconify-icon icon="solar:wallet-outline" class="text-3xl mb-2"></iconify-icon>
								<p class="mb-0">No payout requests yet</p>
							</div>
						<?php else: ?>
							<div class="d-flex flex-column gap-3">
								<?php $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<div class="border-bottom pb-3">
										<div class="d-flex justify-content-between align-items-start mb-1">
											<span class="fw-semibold"><?php echo e(\App\Services\CurrencyService::format($payout->total_amount)); ?></span>
											<span class="badge 
												<?php echo e($payout->status === 'paid' ? 'bg-success' : 
												   ($payout->status === 'processing' ? 'bg-info' : 
												   ($payout->status === 'requested' ? 'bg-warning' : 
												   ($payout->status === 'failed' ? 'bg-danger' : 'bg-secondary')))); ?>">
												<?php echo e(ucfirst($payout->status)); ?>

											</span>
										</div>
										<p class="text-secondary-light small mb-1"><?php echo e($payout->payment_method); ?></p>
										<p class="text-secondary-light small mb-0"><?php echo e($payout->created_at->format('M d, Y')); ?></p>
									</div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="row g-4 mb-24">
		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="card-body p-24">
					<div class="d-flex justify-content-between align-items-center mb-20">
						<h6 class="fw-semibold mb-0">Recent Notifications</h6>
						<a href="<?php echo e(route('notifications.index')); ?>" class="text-sm fw-semibold text-primary-light">View all</a>
					</div>
					<?php if($recentNotifications->isEmpty()): ?>
						<div class="text-center py-32 text-secondary-light">
							<iconify-icon icon="solar:bell-off-outline" class="text-3xl mb-2"></iconify-icon>
							<p class="mb-0">No notifications</p>
						</div>
					<?php else: ?>
						<div class="list-group list-group-flush">
							<?php $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php
									$data = $notification->data;
									$isRead = $notification->read_at !== null;
									$iconColor = $data['icon_color'] ?? 'primary';
									$iconBgClass = "bg-{$iconColor}-focus";
									$iconTextClass = "text-{$iconColor}-main";
								?>
								<a href="<?php echo e($data['url'] ?? '#'); ?>" class="list-group-item list-group-item-action border-0 px-0 py-12 <?php echo e(!$isRead ? 'bg-primary-50' : ''); ?>">
									<div class="d-flex align-items-start gap-3">
										<span class="w-40-px h-40-px rounded-circle flex-shrink-0 <?php echo e($iconBgClass); ?> d-flex justify-content-center align-items-center">
											<iconify-icon icon="<?php echo e($data['icon'] ?? 'solar:bell-outline'); ?>" class="<?php echo e($iconTextClass); ?> text-lg"></iconify-icon>
										</span>
										<div class="flex-grow-1">
											<h6 class="text-sm fw-semibold mb-1"><?php echo e($data['title'] ?? 'Notification'); ?></h6>
											<p class="text-sm text-secondary-light mb-1"><?php echo e($data['message'] ?? ''); ?></p>
											<p class="text-xs text-secondary-light mb-0"><?php echo e($notification->created_at->diffForHumans()); ?></p>
										</div>
									</div>
								</a>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function copyReferralLink() {
	const input = document.getElementById('referralLink');
	input.select();
	document.execCommand('copy');
	alert('Referral link copied to clipboard!');
}

<?php if($affiliate->status === 'active' && isset($analytics)): ?>
// Earnings Over Time Chart
const earningsCtx = document.getElementById('earningsChart');
if (earningsCtx) {
	new Chart(earningsCtx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($analytics['months'], 15, 512) ?>,
			datasets: [{
				label: 'Earnings',
				data: <?php echo json_encode($analytics['earnings_over_time'], 15, 512) ?>,
				borderColor: 'rgb(34, 197, 94)',
				backgroundColor: 'rgba(34, 197, 94, 0.1)',
				tension: 0.4,
				fill: true
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: true,
			plugins: {
				legend: {
					display: false
				}
			},
			scales: {
				y: {
					beginAtZero: true,
					ticks: {
						callback: function(value) {
							return '<?php echo e(\App\Services\CurrencyService::symbol()); ?>' + value.toFixed(2);
						}
					}
				}
			}
		}
	});
}

// Conversion Rate Chart
const conversionCtx = document.getElementById('conversionChart');
if (conversionCtx) {
	const conversionRate = <?php echo e($analytics['conversion_rate'] ?? 0); ?>;
	new Chart(conversionCtx, {
		type: 'doughnut',
		data: {
			labels: ['Converted', 'Not Converted'],
			datasets: [{
				data: [conversionRate, 100 - conversionRate],
				backgroundColor: [
					'rgb(34, 197, 94)',
					'rgb(229, 231, 235)'
				],
				borderWidth: 0
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: true,
			plugins: {
				legend: {
					display: false
				}
			}
		}
	});
}
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.customer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/customer/affiliates/dashboard.blade.php ENDPATH**/ ?>