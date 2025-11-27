@extends('layouts.admin')

@section('title', 'WhatsApp Order Notifications Setup')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">WhatsApp Order Notifications Setup</h6>
			<p class="text-secondary-light mb-0">Complete guide to setting up WhatsApp order notifications.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.integrations.index', ['tab' => 'whatsapp']) }}" class="hover-text-primary">Integrations</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">WhatsApp Setup</li>
		</ul>
	</div>

	<div class="card border-0 mb-24">
		<div class="card-body p-24">
			<div class="alert alert-info">
				<iconify-icon icon="solar:info-circle-bold" class="text-lg"></iconify-icon>
				<strong>Note:</strong> WhatsApp order notifications require Meta Business Account and approved message templates. Follow the steps below to complete setup.
			</div>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Setup Steps</h6>

					<div class="mb-24">
						<h6 class="fw-semibold mb-3">Step 1: Create Meta Business Account</h6>
						<ol class="mb-0">
							<li>Go to <a href="https://business.facebook.com/" target="_blank">Meta Business Suite</a></li>
							<li>Create or access your Business Account</li>
							<li>Add your WhatsApp Business Account</li>
						</ol>
					</div>

					<div class="mb-24">
						<h6 class="fw-semibold mb-3">Step 2: Get API Credentials</h6>
						<ol class="mb-0">
							<li>Go to <a href="https://developers.facebook.com/" target="_blank">Meta for Developers</a></li>
							<li>Create a new app or select existing app</li>
							<li>Add "WhatsApp" product to your app</li>
							<li>Get the following credentials:
								<ul>
									<li><strong>Phone Number ID</strong>: Found in WhatsApp → API Setup</li>
									<li><strong>WhatsApp Business Account ID</strong>: Found in WhatsApp → API Setup</li>
									<li><strong>Business Manager ID</strong>: Found in Business Settings</li>
									<li><strong>App ID</strong>: Found in App Settings → Basic</li>
									<li><strong>App Secret</strong>: Found in App Settings → Basic</li>
									<li><strong>Permanent Access Token</strong>: Generate in WhatsApp → API Setup</li>
								</ul>
							</li>
						</ol>
					</div>

					<div class="mb-24">
						<h6 class="fw-semibold mb-3">Step 3: Configure in Admin Panel</h6>
						<ol class="mb-0">
							<li>Navigate to <a href="{{ route('admin.integrations.index', ['tab' => 'whatsapp']) }}">Admin Panel → Integrations → WhatsApp</a></li>
							<li>Enable "Meta WhatsApp Cloud API"</li>
							<li>Fill in all credentials from Step 2</li>
							<li>Set Webhook Verify Token (create a random secure string)</li>
							<li>Set API Version (default: v19.0)</li>
							<li>Set Language Code (default: en)</li>
							<li>Click "Save Meta Settings"</li>
							<li>Click "Send Test Message" to verify connection</li>
						</ol>
					</div>

					<div class="mb-24">
						<h6 class="fw-semibold mb-3">Step 4: Create Message Templates</h6>
						<p class="mb-3">You need to create approved templates in Meta Business Manager:</p>
						
						<div class="border radius-12 p-3 mb-3">
							<h6 class="fw-semibold mb-2">Template 1: Order Created</h6>
							<ol class="mb-2">
								<li>Go to Meta Business Manager → WhatsApp → Message Templates</li>
								<li>Click "Create Template"</li>
								<li>Template Name: <code>order_created</code> (or your preferred name)</li>
								<li>Category: UTILITY</li>
								<li>Language: English</li>
								<li>Add body text with variables:
									<pre class="bg-neutral-50 p-2 radius-8 mt-2"><code>Hello {{1}}, your order {{2}} has been confirmed. Total amount: {{3}}. Thank you for your purchase!</code></pre>
								</li>
								<li>Variables:
									<ul>
										<li><code>{{1}}</code> = Customer Name</li>
										<li><code>{{2}}</code> = Order Number</li>
										<li><code>{{3}}</code> = Order Total</li>
									</ul>
								</li>
								<li>Submit for approval (usually takes 24-48 hours)</li>
							</ol>
						</div>

						<div class="border radius-12 p-3">
							<h6 class="fw-semibold mb-2">Template 2: Order Status Updated</h6>
							<ol class="mb-2">
								<li>Create another template named <code>order_status_updated</code></li>
								<li>Category: UTILITY</li>
								<li>Body text:
									<pre class="bg-neutral-50 p-2 radius-8 mt-2"><code>Hello {{1}}, your order {{2}} status has been updated from {{3}} to {{4}}. Track your order on our website.</code></pre>
								</li>
								<li>Variables:
									<ul>
										<li><code>{{1}}</code> = Customer Name</li>
										<li><code>{{2}}</code> = Order Number</li>
										<li><code>{{3}}</code> = Old Status</li>
										<li><code>{{4}}</code> = New Status</li>
									</ul>
								</li>
								<li>Submit for approval</li>
							</ol>
						</div>
					</div>

					<div class="mb-24">
						<h6 class="fw-semibold mb-3">Step 5: Configure Template Names</h6>
						<ol class="mb-0">
							<li>In <a href="{{ route('admin.integrations.index', ['tab' => 'whatsapp']) }}">Admin Panel → Integrations → WhatsApp</a></li>
							<li>Enter template names:
								<ul>
									<li><strong>Order Created Template Name</strong>: <code>order_created</code> (or your template name)</li>
									<li><strong>Order Status Updated Template Name</strong>: <code>order_status_updated</code> (or your template name)</li>
								</ul>
							</li>
							<li>Save settings</li>
						</ol>
					</div>

					<div class="mb-24">
						<h6 class="fw-semibold mb-3">Step 6: Test Order Notifications</h6>
						<ol class="mb-0">
							<li>Place a test order in your store</li>
							<li>Check if WhatsApp message is sent to customer</li>
							<li>Update order status and verify status update message</li>
						</ol>
					</div>
				</div>
			</div>

			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Troubleshooting</h6>

					<div class="mb-3">
						<h6 class="fw-semibold mb-2">Messages Not Sending</h6>
						<ul>
							<li>Verify all credentials are correct</li>
							<li>Check if templates are approved in Meta</li>
							<li>Ensure customer phone number is in international format (e.g., +971501234567)</li>
							<li>Check application logs for error messages</li>
						</ul>
					</div>

					<div class="mb-3">
						<h6 class="fw-semibold mb-2">Template Not Found</h6>
						<ul>
							<li>Verify template name matches exactly (case-sensitive)</li>
							<li>Ensure template is approved and active</li>
							<li>Check template language matches your configuration</li>
						</ul>
					</div>

					<div class="mb-3">
						<h6 class="fw-semibold mb-2">Authentication Errors</h6>
						<ul>
							<li>Verify access token is permanent (not temporary)</li>
							<li>Check token has necessary permissions</li>
							<li>Regenerate token if expired</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Quick Links</h6>
					<div class="d-flex flex-column gap-2">
						<a href="{{ route('admin.integrations.index', ['tab' => 'whatsapp']) }}" class="btn btn-outline-primary">
							<iconify-icon icon="solar:settings-linear"></iconify-icon>
							Configure WhatsApp
						</a>
						<a href="https://business.facebook.com/" target="_blank" class="btn btn-outline-secondary">
							<iconify-icon icon="logos:meta"></iconify-icon>
							Meta Business Suite
						</a>
						<a href="https://developers.facebook.com/" target="_blank" class="btn btn-outline-secondary">
							<iconify-icon icon="solar:code-square-outline"></iconify-icon>
							Meta Developers
						</a>
					</div>
				</div>
			</div>

			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Template Variables</h6>
					<div class="mb-3">
						<h6 class="fw-semibold text-sm mb-2">Order Created</h6>
						<ul class="text-sm">
							<li><code>{{1}}</code> - Customer Name</li>
							<li><code>{{2}}</code> - Order Number</li>
							<li><code>{{3}}</code> - Order Total</li>
						</ul>
					</div>
					<div>
						<h6 class="fw-semibold text-sm mb-2">Order Status Updated</h6>
						<ul class="text-sm">
							<li><code>{{1}}</code> - Customer Name</li>
							<li><code>{{2}}</code> - Order Number</li>
							<li><code>{{3}}</code> - Previous Status</li>
							<li><code>{{4}}</code> - New Status</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Important Notes</h6>
					<ul class="text-sm mb-0">
						<li>Templates must be approved by Meta before use</li>
						<li>Phone numbers must be in international format</li>
						<li>Rate limits apply (check Meta documentation)</li>
						<li>Templates cannot be used for marketing</li>
						<li>Customer must have opted in to receive messages</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

