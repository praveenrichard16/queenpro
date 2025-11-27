

<?php $__env->startSection('title', 'Integrations'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Integrations</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Integrations</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<?php if(session('warning')): ?>
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<?php echo e(session('warning')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<?php if(session('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?php echo e(session('error')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<?php if($errors->any()): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please review the highlighted fields below.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-lg-3 mb-24">
			<div class="card border-0 h-100">
				<div class="card-body p-16">
					<div class="nav flex-column nav-pills gap-2" id="integration-tabs" role="tablist" aria-orientation="vertical">
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab', 'email') === 'email' ? 'active' : ''); ?>" id="integration-tab-email" data-bs-toggle="pill" data-bs-target="#integration-pane-email" type="button" role="tab" aria-controls="integration-pane-email" aria-selected="<?php echo e(session('active_tab', 'email') === 'email' ? 'true' : 'false'); ?>">
							<span>Email</span>
							<iconify-icon icon="solar:letter-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'whatsapp' ? 'active' : ''); ?>" id="integration-tab-whatsapp" data-bs-toggle="pill" data-bs-target="#integration-pane-whatsapp" type="button" role="tab" aria-controls="integration-pane-whatsapp" aria-selected="<?php echo e(session('active_tab') === 'whatsapp' ? 'true' : 'false'); ?>">
							<span>WhatsApp</span>
							<iconify-icon icon="logos:whatsapp-icon" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'sms' ? 'active' : ''); ?>" id="integration-tab-sms" data-bs-toggle="pill" data-bs-target="#integration-pane-sms" type="button" role="tab" aria-controls="integration-pane-sms" aria-selected="<?php echo e(session('active_tab') === 'sms' ? 'true' : 'false'); ?>">
							<span>SMS</span>
							<iconify-icon icon="solar:chat-square-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'push' ? 'active' : ''); ?>" id="integration-tab-push" data-bs-toggle="pill" data-bs-target="#integration-pane-push" type="button" role="tab" aria-controls="integration-pane-push" aria-selected="<?php echo e(session('active_tab') === 'push' ? 'true' : 'false'); ?>">
							<span>Push</span>
							<iconify-icon icon="solar:bell-outline" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'social' ? 'active' : ''); ?>" id="integration-tab-social" data-bs-toggle="pill" data-bs-target="#integration-pane-social" type="button" role="tab" aria-controls="integration-pane-social" aria-selected="<?php echo e(session('active_tab') === 'social' ? 'true' : 'false'); ?>">
							<span>Social Login</span>
							<iconify-icon icon="solar:users-group-two-rounded-outline" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'payments' ? 'active' : ''); ?>" id="integration-tab-payments" data-bs-toggle="pill" data-bs-target="#integration-pane-payments" type="button" role="tab" aria-controls="integration-pane-payments" aria-selected="<?php echo e(session('active_tab') === 'payments' ? 'true' : 'false'); ?>">
							<span>Payments</span>
							<iconify-icon icon="solar:card-outline" class="text-lg"></iconify-icon>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="tab-content" id="integration-tabs-content">
				<div class="tab-pane fade <?php echo e(session('active_tab', 'email') === 'email' ? 'show active' : ''); ?>" id="integration-pane-email" role="tabpanel" aria-labelledby="integration-tab-email">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="d-flex justify-content-between align-items-center gap-3 mb-16">
								<div>
									<h6 class="fw-semibold mb-4">SMTP Configuration</h6>
									<p class="text-secondary-light mb-0">Send transactional emails using your SMTP provider.</p>
								</div>
								<span class="badge bg-neutral-200 text-secondary-light fw-medium">
									Twilio SendGrid, Mailgun, Amazon SES or custom SMTP.
								</span>
							</div>
							<form action="<?php echo e(route('admin.integrations.smtp.update')); ?>" method="POST" class="row g-3">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="active_tab" value="email">
								<div class="col-12">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" role="switch" id="smtp-enabled" name="enabled" value="1" <?php echo e(old('enabled', data_get($integrations['smtp'], 'enabled')) ? 'checked' : ''); ?>>
										<label class="form-check-label text-secondary-light" for="smtp-enabled">Enable SMTP delivery</label>
									</div>
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">SMTP Host</label>
									<input type="text" name="host" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('host', data_get($integrations['smtp'], 'host'))); ?>" placeholder="smtp.mailgun.org">
								</div>
								<div class="col-md-3">
									<label class="form-label text-secondary-light">Port</label>
									<input type="number" name="port" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('port', data_get($integrations['smtp'], 'port'))); ?>" placeholder="587">
								</div>
								<div class="col-md-3">
									<label class="form-label text-secondary-light">Encryption</label>
									<select name="encryption" class="form-select bg-neutral-50 radius-12">
										<?php $__currentLoopData = ['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($key); ?>" <?php echo e(old('encryption', data_get($integrations['smtp'], 'encryption')) === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Username</label>
									<input type="text" name="username" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('username', data_get($integrations['smtp'], 'username'))); ?>">
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Password</label>
									<input type="password" name="password" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('password', data_get($integrations['smtp'], 'password'))); ?>" autocomplete="new-password">
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">From Name</label>
									<input type="text" name="from_name" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('from_name', data_get($integrations['smtp'], 'from_name'))); ?>" placeholder="WowDash Store">
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">From Email</label>
									<input type="email" name="from_email" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('from_email', data_get($integrations['smtp'], 'from_email'))); ?>" placeholder="noreply@example.com">
								</div>
								<div class="col-12 d-flex flex-wrap gap-3 mt-12">
									<button type="submit" class="btn btn-primary radius-12 px-24">Save SMTP Settings</button>
									<button type="submit" class="btn btn-outline-secondary radius-12 px-24" formaction="<?php echo e(route('admin.integrations.smtp.test')); ?>">Send Test Email</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(session('active_tab') === 'whatsapp' ? 'show active' : ''); ?>" id="integration-pane-whatsapp" role="tabpanel" aria-labelledby="integration-tab-whatsapp">
					<!-- WhatsApp Status Dashboard -->
					<div class="row g-4 mb-24">
						<div class="col-lg-3 col-md-6">
							<div class="card border-0 bg-base">
								<div class="card-body p-20">
									<div class="d-flex align-items-center justify-content-between mb-12">
										<div>
											<h6 class="text-sm text-secondary-light mb-4">API Connection</h6>
											<h5 class="fw-semibold mb-0"><?php echo e($whatsappStatus['connection']['message'] ?? 'Not configured'); ?></h5>
										</div>
										<div class="w-48-px h-48-px rounded-circle bg-<?php echo e($whatsappStatus['connection']['color'] ?? 'secondary'); ?>-focus d-flex align-items-center justify-content-center">
											<iconify-icon icon="<?php echo e($whatsappStatus['connection']['icon'] ?? 'solar:close-circle-bold'); ?>" class="text-<?php echo e($whatsappStatus['connection']['color'] ?? 'secondary'); ?>-main text-xl"></iconify-icon>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="card border-0 bg-base">
								<div class="card-body p-20">
									<div class="d-flex align-items-center justify-content-between mb-12">
										<div>
											<h6 class="text-sm text-secondary-light mb-4">Catalog</h6>
											<h5 class="fw-semibold mb-0"><?php echo e($whatsappStatus['catalog']['count'] ?? 0); ?> Products</h5>
											<p class="text-xs text-secondary-light mb-0 mt-1"><?php echo e($whatsappStatus['catalog']['message'] ?? 'Not synced'); ?></p>
										</div>
										<div class="w-48-px h-48-px rounded-circle bg-<?php echo e($whatsappStatus['catalog']['color'] ?? 'secondary'); ?>-focus d-flex align-items-center justify-content-center">
											<iconify-icon icon="<?php echo e($whatsappStatus['catalog']['icon'] ?? 'solar:close-circle-bold'); ?>" class="text-<?php echo e($whatsappStatus['catalog']['color'] ?? 'secondary'); ?>-main text-xl"></iconify-icon>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="card border-0 bg-base">
								<div class="card-body p-20">
									<div class="d-flex align-items-center justify-content-between mb-12">
										<div>
											<h6 class="text-sm text-secondary-light mb-4">Templates</h6>
											<h5 class="fw-semibold mb-0"><?php echo e($whatsappStatus['templates']['count'] ?? 0); ?> Templates</h5>
											<p class="text-xs text-secondary-light mb-0 mt-1"><?php echo e($whatsappStatus['templates']['message'] ?? 'Not synced'); ?></p>
										</div>
										<div class="w-48-px h-48-px rounded-circle bg-<?php echo e($whatsappStatus['templates']['color'] ?? 'secondary'); ?>-focus d-flex align-items-center justify-content-center">
											<iconify-icon icon="<?php echo e($whatsappStatus['templates']['icon'] ?? 'solar:close-circle-bold'); ?>" class="text-<?php echo e($whatsappStatus['templates']['color'] ?? 'secondary'); ?>-main text-xl"></iconify-icon>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="card border-0 bg-base">
								<div class="card-body p-20">
									<div class="d-flex align-items-center justify-content-between mb-12">
										<div>
											<h6 class="text-sm text-secondary-light mb-4">Order Notifications</h6>
											<h5 class="fw-semibold mb-0 text-sm"><?php echo e($whatsappStatus['order_notifications']['message'] ?? 'Not configured'); ?></h5>
										</div>
										<div class="w-48-px h-48-px rounded-circle bg-<?php echo e($whatsappStatus['order_notifications']['color'] ?? 'secondary'); ?>-focus d-flex align-items-center justify-content-center">
											<iconify-icon icon="<?php echo e($whatsappStatus['order_notifications']['icon'] ?? 'solar:close-circle-bold'); ?>" class="text-<?php echo e($whatsappStatus['order_notifications']['color'] ?? 'secondary'); ?>-main text-xl"></iconify-icon>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="mb-16">
								<h6 class="fw-semibold mb-4">WhatsApp Integrations</h6>
								<p class="text-secondary-light mb-0">Configure both Twilio WhatsApp messaging and Meta WhatsApp Cloud API.</p>
							</div>

							<div class="border radius-16 p-20 mb-24">
								<div class="d-flex justify-content-between align-items-center gap-3 mb-16">
									<div>
										<h6 class="fw-semibold mb-4 text-secondary">Twilio WhatsApp Messaging</h6>
										<p class="text-secondary-light mb-0">Send notifications through the Twilio Messages API.</p>
									</div>
									<iconify-icon icon="logos:twilio-icon" class="text-2xl"></iconify-icon>
								</div>
								<form action="<?php echo e(route('admin.integrations.whatsapp.twilio.update')); ?>" method="POST" class="row g-3">
									<?php echo csrf_field(); ?>
									<input type="hidden" name="active_tab" value="whatsapp">
									<div class="col-12">
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" id="whatsapp-twilio-enabled" name="enabled" value="1" <?php echo e(old('enabled', data_get($integrations['whatsapp_twilio'], 'enabled')) ? 'checked' : ''); ?>>
											<label class="form-check-label text-secondary-light" for="whatsapp-twilio-enabled">Enable Twilio WhatsApp</label>
										</div>
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Account SID</label>
										<input type="text" name="account_sid" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('account_sid', data_get($integrations['whatsapp_twilio'], 'account_sid'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Auth Token</label>
										<input type="text" name="auth_token" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('auth_token', data_get($integrations['whatsapp_twilio'], 'auth_token'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">WhatsApp Enabled Number</label>
										<input type="text" name="from_number" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('from_number', data_get($integrations['whatsapp_twilio'], 'from_number'))); ?>" placeholder="whatsapp:+14155238886">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Sandbox Number (optional)</label>
										<input type="text" name="sandbox_number" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('sandbox_number', data_get($integrations['whatsapp_twilio'], 'sandbox_number'))); ?>" placeholder="whatsapp:+14155238886">
									</div>
									<div class="col-12 d-flex flex-wrap gap-3 mt-12">
										<button type="submit" class="btn btn-primary radius-12 px-24">Save Twilio Settings</button>
										<button type="submit" class="btn btn-outline-secondary radius-12 px-24" formaction="<?php echo e(route('admin.integrations.whatsapp.twilio.test')); ?>">Send Test Message</button>
									</div>
								</form>
							</div>

							<div class="border radius-16 p-20">
								<div class="d-flex justify-content-between align-items-center gap-3 mb-16">
									<div>
										<h6 class="fw-semibold mb-4 text-secondary">Meta WhatsApp Cloud API</h6>
										<p class="text-secondary-light mb-0">Use the official Meta Cloud API with your Business account.</p>
									</div>
									<iconify-icon icon="bi:meta" class="text-2xl"></iconify-icon>
								</div>
								<form action="<?php echo e(route('admin.integrations.whatsapp.meta.update')); ?>" method="POST" class="row g-3">
									<?php echo csrf_field(); ?>
									<input type="hidden" name="active_tab" value="whatsapp">
									<div class="col-12">
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" id="whatsapp-meta-enabled" name="enabled" value="1" <?php echo e(old('enabled', data_get($integrations['whatsapp_meta'], 'enabled')) ? 'checked' : ''); ?>>
											<label class="form-check-label text-secondary-light" for="whatsapp-meta-enabled">Enable Meta WhatsApp Cloud API</label>
										</div>
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Phone Number ID</label>
										<input type="text" name="phone_number_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('phone_number_id', data_get($integrations['whatsapp_meta'], 'phone_number_id'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">WhatsApp Business Account ID</label>
										<input type="text" name="whatsapp_business_account_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('whatsapp_business_account_id', data_get($integrations['whatsapp_meta'], 'whatsapp_business_account_id'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Business Manager ID</label>
										<input type="text" name="business_account_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('business_account_id', data_get($integrations['whatsapp_meta'], 'business_account_id'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">App ID</label>
										<input type="text" name="app_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('app_id', data_get($integrations['whatsapp_meta'], 'app_id'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">App Secret</label>
										<input type="text" name="app_secret" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('app_secret', data_get($integrations['whatsapp_meta'], 'app_secret'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Permanent Access Token</label>
										<input type="text" name="access_token" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('access_token', data_get($integrations['whatsapp_meta'], 'access_token'))); ?>">
									</div>
									<div class="col-md-12">
										<label class="form-label text-secondary-light">Webhook Verify Token</label>
										<input type="text" name="webhook_verify_token" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('webhook_verify_token', data_get($integrations['whatsapp_meta'], 'webhook_verify_token'))); ?>" placeholder="Enter a secure random token">
										<div class="form-text mt-1">
											<strong>Use this token to validate webhook events from Meta.</strong><br>
											<strong>Webhook URL:</strong> <code><?php echo e(url('/webhook/whatsapp')); ?></code><br>
											<small class="text-muted">
												To configure webhook in Meta Business Manager:<br>
												1. Go to Meta for Developers → Your App → WhatsApp → Configuration<br>
												2. Click "Edit" in the Webhook section<br>
												3. Enter the Webhook URL above<br>
												4. Enter the Verify Token (same as above)<br>
												5. Subscribe to: <code>messages</code>, <code>message_template_status_update</code><br>
												6. Click "Verify and Save"
											</small>
										</div>
									</div>
									<div class="col-md-4">
										<label class="form-label text-secondary-light">API Version</label>
										<input type="text" name="api_version" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('api_version', data_get($integrations['whatsapp_meta'], 'api_version'))); ?>" placeholder="v19.0">
									</div>
									<div class="col-md-4">
										<label class="form-label text-secondary-light">Language Code</label>
										<input type="text" name="language" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('language', data_get($integrations['whatsapp_meta'], 'language'))); ?>" placeholder="en">
									</div>
									<div class="col-md-4"></div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Order Created Template Name</label>
										<input type="text" name="template_order_created" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('template_order_created', data_get($integrations['whatsapp_meta'], 'template_order_created'))); ?>" placeholder="your_order_created_template">
										<div class="form-text mt-1">Meta approved template used when a new order is placed.</div>
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Order Status Updated Template Name</label>
										<input type="text" name="template_order_status_updated" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('template_order_status_updated', data_get($integrations['whatsapp_meta'], 'template_order_status_updated'))); ?>" placeholder="your_order_status_updated_template">
										<div class="form-text mt-1">Meta approved template used when order status changes.</div>
									</div>
									<div class="col-12">
										<label class="form-label text-secondary-light">Test Phone Number <span class="text-danger">*</span> (for test message)</label>
										<input type="text" name="test_phone_number" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('test_phone_number')); ?>" placeholder="971501234567 (with country code, no +)">
										<div class="form-text mt-1">Enter phone number in international format (e.g., 971501234567) to receive test message. Include country code without + sign.</div>
									</div>
									<div class="col-12 d-flex flex-wrap gap-3 mt-12">
										<button type="submit" class="btn btn-primary radius-12 px-24">Save Meta Settings</button>
										<button type="submit" class="btn btn-outline-secondary radius-12 px-24" formaction="<?php echo e(route('admin.integrations.whatsapp.meta.test')); ?>">Send Test Message</button>
										<a href="<?php echo e(route('admin.integrations.whatsapp.order-notifications-docs')); ?>" class="btn btn-info radius-12 px-24 text-white">
											<iconify-icon icon="solar:document-text-outline"></iconify-icon>
											Setup Guide
										</a>
										<a href="<?php echo e(route('admin.whatsapp-catalog.index')); ?>" class="btn btn-success radius-12 px-24 text-white">
											<iconify-icon icon="solar:shop-2-outline"></iconify-icon>
											Manage Catalog
										</a>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(session('active_tab') === 'sms' ? 'show active' : ''); ?>" id="integration-pane-sms" role="tabpanel" aria-labelledby="integration-tab-sms">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="d-flex justify-content-between align-items-center gap-3 mb-16">
								<div>
									<h6 class="fw-semibold mb-4">SMS Gateway (Twilio)</h6>
									<p class="text-secondary-light mb-0">Configure SMS alerts, OTPs, and marketing campaigns.</p>
								</div>
								<iconify-icon icon="logos:twilio-icon" class="text-2xl"></iconify-icon>
							</div>
							<form action="<?php echo e(route('admin.integrations.sms.twilio.update')); ?>" method="POST" class="row g-3">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="active_tab" value="sms">
								<div class="col-12">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" role="switch" id="sms-twilio-enabled" name="enabled" value="1" <?php echo e(old('enabled', data_get($integrations['sms_twilio'], 'enabled')) ? 'checked' : ''); ?>>
										<label class="form-check-label text-secondary-light" for="sms-twilio-enabled">Enable Twilio SMS</label>
									</div>
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Account SID</label>
									<input type="text" name="account_sid" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('account_sid', data_get($integrations['sms_twilio'], 'account_sid'))); ?>">
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Auth Token</label>
									<input type="text" name="auth_token" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('auth_token', data_get($integrations['sms_twilio'], 'auth_token'))); ?>">
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">From Number</label>
									<input type="text" name="from_number" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('from_number', data_get($integrations['sms_twilio'], 'from_number'))); ?>" placeholder="+971500000000">
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Messaging Service SID (optional)</label>
									<input type="text" name="messaging_service_sid" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('messaging_service_sid', data_get($integrations['sms_twilio'], 'messaging_service_sid'))); ?>">
								</div>
								<div class="col-12 d-flex flex-wrap gap-3 mt-12">
									<button type="submit" class="btn btn-primary radius-12 px-24">Save SMS Settings</button>
									<button type="submit" class="btn btn-outline-secondary radius-12 px-24" formaction="<?php echo e(route('admin.integrations.sms.twilio.test')); ?>">Send Test SMS</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(session('active_tab') === 'push' ? 'show active' : ''); ?>" id="integration-pane-push" role="tabpanel" aria-labelledby="integration-tab-push">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="d-flex justify-content-between align-items-center gap-3 mb-16">
								<div>
									<h6 class="fw-semibold mb-4">Push Notifications (Firebase)</h6>
									<p class="text-secondary-light mb-0">Send in-app and web push notifications via Firebase Cloud Messaging.</p>
								</div>
								<iconify-icon icon="logos:firebase" class="text-2xl"></iconify-icon>
							</div>
							<form action="<?php echo e(route('admin.integrations.push.fcm.update')); ?>" method="POST" class="row g-3">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="active_tab" value="push">
								<div class="col-12">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" role="switch" id="push-fcm-enabled" name="enabled" value="1" <?php echo e(old('enabled', data_get($integrations['push_fcm'], 'enabled')) ? 'checked' : ''); ?>>
										<label class="form-check-label text-secondary-light" for="push-fcm-enabled">Enable Firebase Cloud Messaging</label>
									</div>
								</div>
								<div class="col-md-12">
									<label class="form-label text-secondary-light">Server Key</label>
									<textarea name="server_key" rows="3" class="form-control bg-neutral-50 radius-12"><?php echo e(old('server_key', data_get($integrations['push_fcm'], 'server_key'))); ?></textarea>
									<div class="form-text mt-1">Copy the key from Firebase console &raquo; Project settings &raquo; Cloud Messaging.</div>
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Sender ID</label>
									<input type="text" name="sender_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('sender_id', data_get($integrations['push_fcm'], 'sender_id'))); ?>">
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Project ID</label>
									<input type="text" name="project_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('project_id', data_get($integrations['push_fcm'], 'project_id'))); ?>">
								</div>
								<div class="col-12 d-flex flex-wrap gap-3 mt-12">
									<button type="submit" class="btn btn-primary radius-12 px-24">Save Push Settings</button>
									<button type="submit" class="btn btn-outline-secondary radius-12 px-24" formaction="<?php echo e(route('admin.integrations.push.fcm.test')); ?>">Send Test Push</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(session('active_tab') === 'social' ? 'show active' : ''); ?>" id="integration-pane-social" role="tabpanel" aria-labelledby="integration-tab-social">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-16">Social Login Providers</h6>
							<div class="border radius-16 p-20 mb-24">
								<div class="d-flex justify-content-between align-items-center gap-3 mb-16">
									<div>
										<h6 class="fw-semibold mb-4 text-secondary">Google Login</h6>
										<p class="text-secondary-light mb-0">Allow customers to sign in using their Google accounts.</p>
									</div>
									<iconify-icon icon="logos:google-icon" class="text-2xl"></iconify-icon>
								</div>
								<form action="<?php echo e(route('admin.integrations.social.google.update')); ?>" method="POST" class="row g-3">
									<?php echo csrf_field(); ?>
									<input type="hidden" name="active_tab" value="social">
									<div class="col-12">
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" id="social-google-enabled" name="enabled" value="1" <?php echo e(old('enabled', data_get($integrations['social_google'], 'enabled')) ? 'checked' : ''); ?>>
											<label class="form-check-label text-secondary-light" for="social-google-enabled">Enable Google Login</label>
										</div>
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Client ID</label>
										<input type="text" name="client_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('client_id', data_get($integrations['social_google'], 'client_id'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">Client Secret</label>
										<input type="text" name="client_secret" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('client_secret', data_get($integrations['social_google'], 'client_secret'))); ?>">
									</div>
									<div class="col-12">
										<label class="form-label text-secondary-light">Redirect URI</label>
										<input type="url" name="redirect_uri" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('redirect_uri', data_get($integrations['social_google'], 'redirect_uri') ?? url('/auth/google/callback'))); ?>">
										<div class="form-text mt-1">Add this redirect URL in Google Cloud Console OAuth credentials.</div>
									</div>
									<div class="col-12 d-flex flex-wrap gap-3 mt-12">
										<button type="submit" class="btn btn-primary radius-12 px-24">Save Google Settings</button>
										<button type="submit" class="btn btn-outline-secondary radius-12 px-24" formaction="<?php echo e(route('admin.integrations.social.google.test')); ?>">Verify OAuth</button>
									</div>
								</form>
							</div>

							<div class="border radius-16 p-20">
								<div class="d-flex justify-content-between align-items-center gap-3 mb-16">
									<div>
										<h6 class="fw-semibold mb-4 text-secondary">Facebook Login</h6>
										<p class="text-secondary-light mb-0">Enable login via Facebook / Meta accounts.</p>
									</div>
									<iconify-icon icon="logos:facebook" class="text-2xl"></iconify-icon>
								</div>
								<form action="<?php echo e(route('admin.integrations.social.facebook.update')); ?>" method="POST" class="row g-3">
									<?php echo csrf_field(); ?>
									<input type="hidden" name="active_tab" value="social">
									<div class="col-12">
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" id="social-facebook-enabled" name="enabled" value="1" <?php echo e(old('enabled', data_get($integrations['social_facebook'], 'enabled')) ? 'checked' : ''); ?>>
											<label class="form-check-label text-secondary-light" for="social-facebook-enabled">Enable Facebook Login</label>
										</div>
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">App ID</label>
										<input type="text" name="app_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('app_id', data_get($integrations['social_facebook'], 'app_id'))); ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label text-secondary-light">App Secret</label>
										<input type="text" name="app_secret" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('app_secret', data_get($integrations['social_facebook'], 'app_secret'))); ?>">
									</div>
									<div class="col-12">
										<label class="form-label text-secondary-light">Redirect URI</label>
										<input type="url" name="redirect_uri" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('redirect_uri', data_get($integrations['social_facebook'], 'redirect_uri') ?? url('/auth/facebook/callback'))); ?>">
										<div class="form-text mt-1">Add this redirect URL in Meta Developers &raquo; Facebook Login &raquo; Settings.</div>
									</div>
									<div class="col-12 d-flex flex-wrap gap-3 mt-12">
										<button type="submit" class="btn btn-primary radius-12 px-24">Save Facebook Settings</button>
										<button type="submit" class="btn btn-outline-secondary radius-12 px-24" formaction="<?php echo e(route('admin.integrations.social.facebook.test')); ?>">Verify OAuth</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(session('active_tab') === 'payments' ? 'show active' : ''); ?>" id="integration-pane-payments" role="tabpanel" aria-labelledby="integration-tab-payments">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-16">Payment Gateways</h6>

							<div class="border radius-16 p-20">
								<div class="d-flex justify-content-between align-items-center gap-3 mb-16">
									<div>
										<h6 class="fw-semibold mb-4 text-secondary">Razorpay</h6>
										<p class="text-secondary-light mb-0">Accept payments via Razorpay - cards, UPI, netbanking, wallets, and more.</p>
									</div>
									<iconify-icon icon="solar:card-outline" class="text-2xl text-primary"></iconify-icon>
								</div>
								<form action="<?php echo e(route('admin.integrations.payments.razorpay.update')); ?>" method="POST" class="row g-3">
									<?php echo csrf_field(); ?>
									<input type="hidden" name="active_tab" value="payments">
									<div class="col-12">
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" id="payments-razorpay-enabled" name="enabled" value="1" <?php echo e(old('enabled', data_get($integrations['payments_razorpay'], 'enabled')) ? 'checked' : ''); ?>>
											<label class="form-check-label text-secondary-light" for="payments-razorpay-enabled">Enable Razorpay</label>
										</div>
									</div>
									<div class="col-md-4">
										<label class="form-label text-secondary-light">Mode</label>
										<select name="mode" class="form-select bg-neutral-50 radius-12">
											<option value="sandbox" <?php echo e(old('mode', data_get($integrations['payments_razorpay'], 'mode')) === 'sandbox' ? 'selected' : ''); ?>>Sandbox (Test)</option>
											<option value="live" <?php echo e(old('mode', data_get($integrations['payments_razorpay'], 'mode')) === 'live' ? 'selected' : ''); ?>>Live</option>
										</select>
									</div>
									<div class="col-md-4">
										<label class="form-label text-secondary-light">Key ID <span class="text-danger">*</span></label>
										<input type="text" name="key_id" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('key_id', data_get($integrations['payments_razorpay'], 'key_id'))); ?>" placeholder="rzp_test_... or rzp_live_...">
										<div class="form-text mt-1">Your Razorpay API Key ID</div>
									</div>
									<div class="col-md-4">
										<label class="form-label text-secondary-light">Key Secret <span class="text-danger">*</span></label>
										<input type="text" name="key_secret" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('key_secret', data_get($integrations['payments_razorpay'], 'key_secret'))); ?>" placeholder="Your Razorpay API Secret">
										<div class="form-text mt-1">Your Razorpay API Key Secret</div>
									</div>
									<div class="col-12">
										<label class="form-label text-secondary-light">Webhook Secret (optional)</label>
										<input type="text" name="webhook_secret" class="form-control bg-neutral-50 radius-12" value="<?php echo e(old('webhook_secret', data_get($integrations['payments_razorpay'], 'webhook_secret'))); ?>" placeholder="Webhook signing secret">
										<div class="form-text mt-1">Used to verify webhook events from Razorpay. Get this from Razorpay Dashboard → Settings → Webhooks.</div>
									</div>
									<div class="col-12 d-flex flex-wrap gap-3 mt-12">
										<button type="submit" class="btn btn-primary radius-12 px-24">Save Razorpay Settings</button>
										<button type="submit" class="btn btn-outline-secondary radius-12 px-24" formaction="<?php echo e(route('admin.integrations.payments.razorpay.test')); ?>">Verify Credentials</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
	#integration-tabs .btn {
		width: 100%;
		justify-content: space-between;
	}

	#integration-tabs .btn.active {
		background-color: #024550 !important;
		color: #ffffff !important;
		border-color: #024550 !important;
	}

	#integration-tabs .btn:not(.active):hover {
		background-color: rgba(2, 69, 80, 0.08);
	}
</style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/integrations/index.blade.php ENDPATH**/ ?>