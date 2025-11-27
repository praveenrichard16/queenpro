@extends('layouts.admin')

@section('title', 'API Documentation')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">API Documentation</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">API Documentation</li>
		</ul>
	</div>

	<div class="row g-4">
		<!-- Left Sidebar - API Sections -->
		<div class="col-lg-3">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">API Sections</h6>
					<div class="d-flex flex-column gap-2">
						<a href="#getting-started" class="btn btn-primary radius-12 text-start justify-content-start" data-section="getting-started">
							<iconify-icon icon="solar:rocket-2-outline" class="me-2"></iconify-icon>
							Getting Started
						</a>
						<a href="#authentication" class="btn btn-outline-secondary radius-12 text-start justify-content-start" data-section="authentication">
							<iconify-icon icon="solar:key-outline" class="me-2"></iconify-icon>
							Authentication
						</a>
						<a href="#token-management" class="btn btn-outline-secondary radius-12 text-start justify-content-start" data-section="token-management">
							<iconify-icon icon="solar:key-minimalistic-2-outline" class="me-2"></iconify-icon>
							Token Management
						</a>
						<div class="mt-3">
							<h6 class="fw-semibold text-sm mb-8">E-commerce APIs</h6>
							<div class="d-flex flex-column gap-2">
								<a href="#products-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="products-api">
									<iconify-icon icon="solar:bag-smile-outline" class="me-2"></iconify-icon>
									Products API
								</a>
								<a href="#reviews-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="reviews-api">
									<iconify-icon icon="solar:star-outline" class="me-2"></iconify-icon>
									Reviews API
								</a>
								<a href="#orders-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="orders-api">
									<iconify-icon icon="solar:bag-check-outline" class="me-2"></iconify-icon>
									Orders API
								</a>
								<a href="#cart-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="cart-api">
									<iconify-icon icon="solar:cart-outline" class="me-2"></iconify-icon>
									Cart API
								</a>
								<a href="#coupons-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="coupons-api">
									<iconify-icon icon="solar:ticket-outline" class="me-2"></iconify-icon>
									Coupons API
								</a>
								<a href="#categories-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="categories-api">
									<iconify-icon icon="solar:widget-4-outline" class="me-2"></iconify-icon>
									Categories API
								</a>
								<a href="#brands-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="brands-api">
									<iconify-icon icon="solar:medal-ribbon-outline" class="me-2"></iconify-icon>
									Brands API
								</a>
							</div>
						</div>
						<div class="mt-3">
							<h6 class="fw-semibold text-sm mb-8">CRM APIs</h6>
							<div class="d-flex flex-column gap-2">
								<a href="#leads-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="leads-api">
									<iconify-icon icon="solar:user-id-outline" class="me-2"></iconify-icon>
									Leads API
								</a>
								<a href="#enquiries-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="enquiries-api">
									<iconify-icon icon="solar:question-circle-outline" class="me-2"></iconify-icon>
									Enquiries API
								</a>
								<a href="#quotations-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="quotations-api">
									<iconify-icon icon="solar:document-outline" class="me-2"></iconify-icon>
									Quotations API
								</a>
								<a href="#invoices-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="invoices-api">
									<iconify-icon icon="solar:receipt-outline" class="me-2"></iconify-icon>
									Invoices API
								</a>
							</div>
						</div>
						<div class="mt-3">
							<h6 class="fw-semibold text-sm mb-8">Content & Support</h6>
							<div class="d-flex flex-column gap-2">
								<a href="#blog-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="blog-api">
									<iconify-icon icon="solar:document-text-outline" class="me-2"></iconify-icon>
									Blog API
								</a>
								<a href="#tickets-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="tickets-api">
									<iconify-icon icon="solar:lifebuoy-outline" class="me-2"></iconify-icon>
									Tickets API
								</a>
								<a href="#affiliates-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="affiliates-api">
									<iconify-icon icon="solar:users-group-two-rounded-outline" class="me-2"></iconify-icon>
									Affiliates API
								</a>
							</div>
						</div>
						<div class="mt-3">
							<h6 class="fw-semibold text-sm mb-8">Users & Settings</h6>
							<div class="d-flex flex-column gap-2">
								<a href="#users-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="users-api">
									<iconify-icon icon="solar:user-outline" class="me-2"></iconify-icon>
									Users API
								</a>
								<a href="#shipping-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="shipping-api">
									<iconify-icon icon="solar:delivery-outline" class="me-2"></iconify-icon>
									Shipping API
								</a>
								<a href="#tax-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="tax-api">
									<iconify-icon icon="solar:receipt-outline" class="me-2"></iconify-icon>
									Tax API
								</a>
								<a href="#commands-api" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="commands-api">
									<iconify-icon icon="solar:terminal-outline" class="me-2"></iconify-icon>
									Commands API
								</a>
							</div>
						</div>
						<div class="mt-3">
							<h6 class="fw-semibold text-sm mb-8">Reference</h6>
							<div class="d-flex flex-column gap-2">
								<a href="#error-handling" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="error-handling">
									<iconify-icon icon="solar:danger-triangle-outline" class="me-2"></iconify-icon>
									Error Handling
								</a>
								<a href="#additional-info" class="btn btn-outline-secondary radius-12 text-start justify-content-start text-sm" data-section="additional-info">
									<iconify-icon icon="solar:info-circle-outline" class="me-2"></iconify-icon>
									Additional Info
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Main Content Area -->
		<div class="col-lg-9">
			<!-- Base URL Section -->
			<div class="card border-0 mb-24">
				<div class="card-body p-24">
					<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
						<div>
							<h5 class="fw-semibold mb-2 d-flex align-items-center gap-2">
								<iconify-icon icon="solar:link-outline"></iconify-icon>
								Base URL
							</h5>
							<code class="text-danger">{{ url('/api/v1') }}</code>
						</div>
						<div class="d-flex gap-2">
							<button type="button" class="btn btn-outline-secondary radius-12" onclick="copyToClipboard('{{ url('/api/v1') }}')">
								<iconify-icon icon="solar:copy-outline" class="me-1"></iconify-icon>
								Copy
							</button>
							<a href="{{ route('admin.api.test-console') }}" class="btn btn-primary radius-12">
								<iconify-icon icon="solar:code-square-outline" class="me-1"></iconify-icon>
								Test API
							</a>
							<a href="{{ route('admin.api-tokens.index') }}" class="btn btn-outline-secondary radius-12">
								<iconify-icon icon="solar:key-outline" class="me-1"></iconify-icon>
								Tokens
							</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Getting Started Section -->
			<div id="getting-started" class="api-section">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:rocket-2-outline"></iconify-icon>
							Getting Started
						</h5>
						<p class="mb-16">
							Welcome to the E-commerce API documentation. This guide will help you integrate with our API to manage products, orders, customers, leads, enquiries, blog posts, and more.
						</p>
						<h6 class="fw-semibold mb-12">Quick Start</h6>
						<ol class="mb-0">
							<li class="mb-2">Get your API token from the <a href="{{ route('admin.api-tokens.index') }}">Token Management</a> page</li>
							<li class="mb-2">Use the token in the <code class="text-danger">Authorization: Bearer YOUR_TOKEN</code> header for all requests</li>
							<li class="mb-2">Start making API calls to the endpoints listed in this documentation</li>
							<li class="mb-0">Test your integration using the <a href="{{ route('admin.api.test-console') }}">API Testing Interface</a></li>
						</ol>
						<div class="alert alert-info mt-16">
							<iconify-icon icon="solar:info-circle-bold"></iconify-icon>
							<strong>Base URL:</strong> All API requests should be made to <code>{{ url('/api') }}</code>
						</div>
					</div>
				</div>
			</div>

			<!-- Authentication Section -->
			<div id="authentication" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:key-outline"></iconify-icon>
							Authentication
						</h5>
						<p class="mb-16">
							All API requests require authentication using Bearer tokens. Tokens are created using Laravel Sanctum and must be included in the Authorization header.
						</p>
						<h6 class="fw-semibold mb-12">Using Bearer Tokens</h6>
						<pre class="bg-neutral-50 p-16 radius-12 mb-16"><code>Authorization: Bearer YOUR_API_TOKEN</code></pre>
						<h6 class="fw-semibold mb-12">Example Request</h6>
						<pre class="bg-neutral-50 p-16 radius-12 mb-0"><code>curl -X GET "{{ url('/api/v1') }}/leads" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Accept: application/json"</code></pre>
					</div>
				</div>
			</div>

			<!-- Token Management Section -->
			<div id="token-management" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:key-minimalistic-2-outline"></iconify-icon>
							Token Management
						</h5>
						<p class="mb-16">
							API tokens are used to authenticate requests. You can create, manage, and revoke tokens from the <a href="{{ route('admin.api-tokens.index') }}">API Token Management</a> page.
						</p>
						<h6 class="fw-semibold mb-12">Creating a Token</h6>
						<ol class="mb-16">
							<li>Navigate to <strong>API Access → API Keys</strong></li>
							<li>Click <strong>Create API Key</strong></li>
							<li>Enter a descriptive name for your token</li>
							<li>Select the user and permissions</li>
							<li>Copy the token immediately (it won't be shown again)</li>
						</ol>
						<h6 class="fw-semibold mb-12">Token Permissions</h6>
						<ul class="mb-0">
							<li><strong>Read:</strong> View and list resources</li>
							<li><strong>Write:</strong> Create and update resources</li>
							<li><strong>Delete:</strong> Delete resources</li>
							<li><strong>Admin:</strong> Full administrative access</li>
							<li><strong>All (*):</strong> All permissions (default if none specified)</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Leads API Section -->
			<div id="leads-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:user-id-outline"></iconify-icon>
							Leads API
						</h5>
						<p class="mb-16">Comprehensive lead management API with analytics, followups, scoring, import/export, and more.</p>
						
						<h6 class="fw-semibold mb-12 mt-24">Basic Lead Operations</h6>
						<ul class="mb-16">
							<li><code>GET /api/v1/leads</code> - List all leads (supports search, stage_id, source_id filters, pagination)</li>
							<li><code>GET /api/v1/leads/{id}</code> - Get lead details with relationships</li>
							<li><code>POST /api/v1/leads</code> - Create a new lead (requires: name, email; optional: phone, lead_source_id, lead_stage_id, expected_value, notes, assigned_to, next_followup_date, next_followup_time, lead_score)</li>
							<li><code>PUT /api/v1/leads/{id}</code> - Update a lead</li>
							<li><code>DELETE /api/v1/leads/{id}</code> - Soft delete a lead</li>
						</ul>

						<h6 class="fw-semibold mb-12">Lead Analytics</h6>
						<ul class="mb-16">
							<li><code>GET /api/v1/leads/analytics/overview</code> - Get lead analytics (total leads, by stage, by source, conversion rate, trends)</li>
						</ul>

						<h6 class="fw-semibold mb-12">Lead Scoring</h6>
						<ul class="mb-16">
							<li><code>POST /api/v1/leads/{id}/recalculate-score</code> - Recalculate lead score based on stage, value, source, activity, and followups</li>
						</ul>

						<h6 class="fw-semibold mb-12">Trash Management</h6>
						<ul class="mb-16">
							<li><code>GET /api/v1/leads/trash</code> - List soft-deleted leads</li>
							<li><code>POST /api/v1/leads/{id}/restore</code> - Restore a deleted lead</li>
							<li><code>DELETE /api/v1/leads/{id}/force-delete</code> - Permanently delete a lead</li>
						</ul>

						<h6 class="fw-semibold mb-12">Lead Followups</h6>
						<ul class="mb-16">
							<li><code>GET /api/v1/leads/{lead}/followups</code> - List all followups for a lead</li>
							<li><code>POST /api/v1/leads/{lead}/followups</code> - Create a new followup (requires: followup_date; optional: followup_time, notes, status, outcome)</li>
							<li><code>GET /api/v1/leads/{lead}/followups/{followup}</code> - Get followup details</li>
							<li><code>PUT /api/v1/leads/{lead}/followups/{followup}</code> - Update a followup</li>
							<li><code>DELETE /api/v1/leads/{lead}/followups/{followup}</code> - Delete a followup</li>
							<li><code>POST /api/v1/leads/{lead}/followups/{followup}/complete</code> - Mark followup as completed (optional: outcome, notes)</li>
							<li><code>POST /api/v1/leads/{lead}/followups/{followup}/cancel</code> - Cancel a followup (optional: notes)</li>
							<li><code>POST /api/v1/leads/{lead}/followups/{followup}/send-reminder</code> - Manually trigger reminder for a followup</li>
						</ul>

						<h6 class="fw-semibold mb-12">Lead Import/Export</h6>
						<ul class="mb-16">
							<li><code>GET /api/v1/leads/export</code> - Export leads to CSV (supports filters: search, stage_id, source_id, format=csv|xlsx)</li>
							<li><code>POST /api/v1/leads/import</code> - Import leads from CSV/Excel file (supports mode: create|update|replace)</li>
						</ul>

						<h6 class="fw-semibold mb-12">Lead Reminders</h6>
						<ul class="mb-16">
							<li><code>POST /api/v1/leads/reminders/run</code> - Manually trigger followup reminders for due followups (sends via email/SMS/WhatsApp)</li>
						</ul>

						<h6 class="fw-semibold mb-12">Lead Sources & Stages</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/lead-sources</code> - List all lead sources</li>
							<li><code>GET /api/v1/lead-sources/{id}</code> - Get lead source details</li>
							<li><code>GET /api/v1/lead-stages</code> - List all lead stages</li>
							<li><code>GET /api/v1/lead-stages/{id}</code> - Get lead stage details</li>
						</ul>

						<div class="alert alert-info mt-16">
							<h6 class="fw-semibold mb-2">Request/Response Examples</h6>
							<p class="mb-2"><strong>Create Lead:</strong></p>
							<pre class="mb-3"><code>POST /api/v1/leads
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+919876543210",
  "lead_source_id": 1,
  "lead_stage_id": 1,
  "expected_value": 5000,
  "notes": "Interested in premium package",
  "next_followup_date": "2024-12-01",
  "next_followup_time": "14:00"
}</code></pre>
							<p class="mb-2"><strong>Create Followup:</strong></p>
							<pre class="mb-0"><code>POST /api/v1/leads/1/followups
{
  "followup_date": "2024-12-01",
  "followup_time": "14:00",
  "notes": "Call to discuss pricing",
  "status": "scheduled"
}</code></pre>
						</div>
					</div>
				</div>
			</div>


			<!-- Products API Section -->
			<div id="products-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:bag-smile-outline"></iconify-icon>
							Products API
						</h5>
						<p class="mb-16">Browse and manage products in the catalog.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/products</code> - List all products (supports search, category_id, brand_id, is_active, is_featured, in_stock filters)</li>
							<li><code>GET /api/v1/products/{id}</code> - Get product details with reviews</li>
							<li><code>GET /api/v1/products/{product}/reviews</code> - Get reviews for a product</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Reviews API Section -->
			<div id="reviews-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:star-outline"></iconify-icon>
							Product Reviews API
						</h5>
						<p class="mb-16">Manage product reviews and ratings.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/reviews</code> - List all approved reviews (supports product_id, user_id filters)</li>
							<li><code>GET /api/v1/reviews/{id}</code> - Get review details</li>
							<li><code>POST /api/v1/reviews</code> - Create a new review (requires product_id, rating, comment)</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Orders API Section -->
			<div id="orders-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:bag-check-outline"></iconify-icon>
							Orders API
						</h5>
						<p class="mb-16">Manage customer orders.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/orders</code> - List orders (filtered by user access)</li>
							<li><code>GET /api/v1/orders/{id}</code> - Get order details with items</li>
							<li><code>POST /api/v1/orders</code> - Create a new order</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Cart API Section -->
			<div id="cart-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:cart-outline"></iconify-icon>
							Cart API
						</h5>
						<p class="mb-16">Manage shopping cart operations.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/cart</code> - Get current cart contents</li>
							<li><code>POST /api/v1/cart/add</code> - Add item to cart (requires product_id, quantity)</li>
							<li><code>PUT /api/v1/cart/update</code> - Update cart item quantity</li>
							<li><code>DELETE /api/v1/cart/remove</code> - Remove item from cart</li>
							<li><code>DELETE /api/v1/cart/clear</code> - Clear entire cart</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Coupons API Section -->
			<div id="coupons-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:ticket-outline"></iconify-icon>
							Coupons API
						</h5>
						<p class="mb-16">Manage discount coupons and offers.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/coupons</code> - List available coupons</li>
							<li><code>GET /api/v1/coupons/{id}</code> - Get coupon details</li>
							<li><code>POST /api/v1/coupons/validate</code> - Validate a coupon code (requires code, optional amount)</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Categories API Section -->
			<div id="categories-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:widget-4-outline"></iconify-icon>
							Categories API
						</h5>
						<p class="mb-16">Browse product categories.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/categories</code> - List all categories</li>
							<li><code>GET /api/v1/categories/{id}</code> - Get category details</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Brands API Section -->
			<div id="brands-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:medal-ribbon-outline"></iconify-icon>
							Brands API
						</h5>
						<p class="mb-16">Browse product brands.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/brands</code> - List all brands</li>
							<li><code>GET /api/v1/brands/{id}</code> - Get brand details</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Enquiries API Section -->
			<div id="enquiries-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:document-text-outline"></iconify-icon>
							Enquiries API
						</h5>
						<p class="mb-16">Manage customer enquiries and inquiries.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/enquiries</code> - List enquiries (filtered by user access, supports status, product_id filters)</li>
							<li><code>GET /api/v1/enquiries/{id}</code> - Get enquiry details</li>
							<li><code>POST /api/v1/enquiries</code> - Create a new enquiry (requires subject, message)</li>
							<li><code>PUT /api/v1/enquiries/{id}</code> - Update enquiry status</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Blog API Section -->
			<div id="blog-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:document-text-outline"></iconify-icon>
							Blog API
						</h5>
						<p class="mb-16">Browse blog posts and categories.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/blog/posts</code> - List blog posts (supports search, category_id, is_featured filters)</li>
							<li><code>GET /api/v1/blog/posts/{id}</code> - Get blog post details</li>
							<li><code>GET /api/v1/blog/categories</code> - List blog categories</li>
							<li><code>GET /api/v1/blog/categories/{id}</code> - Get blog category details</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Tickets API Section -->
			<div id="tickets-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:lifebuoy-outline"></iconify-icon>
							Support Tickets API
						</h5>
						<p class="mb-16">Manage support tickets and customer service requests.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/tickets</code> - List tickets (filtered by user access)</li>
							<li><code>GET /api/v1/tickets/{id}</code> - Get ticket details with messages</li>
							<li><code>POST /api/v1/tickets</code> - Create a new ticket (requires subject, description)</li>
							<li><code>PUT /api/v1/tickets/{id}</code> - Update ticket status or priority</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Affiliates API Section -->
			<div id="affiliates-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:users-group-two-rounded-outline"></iconify-icon>
							Affiliates API
						</h5>
						<p class="mb-16">Manage affiliate accounts and referral codes.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/affiliates</code> - List affiliates (admin only)</li>
							<li><code>GET /api/v1/affiliates/{id}</code> - Get affiliate details</li>
							<li><code>GET /api/v1/affiliates/me</code> - Get current user's affiliate info</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Users API Section -->
			<div id="users-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:user-outline"></iconify-icon>
							Users API
						</h5>
						<p class="mb-16">Manage user accounts and profiles.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/me</code> - Get current authenticated user</li>
							<li><code>GET /api/v1/users</code> - List users (admin only, supports type filter)</li>
							<li><code>GET /api/v1/users/{id}</code> - Get user details</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Shipping API Section -->
			<div id="shipping-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:delivery-outline"></iconify-icon>
							Shipping Methods API
						</h5>
						<p class="mb-16">Browse available shipping methods.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/shipping-methods</code> - List all shipping methods</li>
							<li><code>GET /api/v1/shipping-methods/{id}</code> - Get shipping method details</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Tax API Section -->
			<div id="tax-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:receipt-outline"></iconify-icon>
							Tax Classes API
						</h5>
						<p class="mb-16">Browse tax classes and rates.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/tax-classes</code> - List all tax classes</li>
							<li><code>GET /api/v1/tax-classes/{id}</code> - Get tax class details</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Commands API Section -->
			<div id="commands-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:terminal-outline"></iconify-icon>
							Commands API
						</h5>
						<p class="mb-16">Execute system commands (admin only).</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/commands</code> - List available commands</li>
							<li><code>POST /api/v1/commands/execute</code> - Execute a system command (requires command, optional parameters)</li>
						</ul>
						<div class="alert alert-warning mt-16">
							<iconify-icon icon="solar:danger-triangle-bold"></iconify-icon>
							<strong>Admin Only:</strong> These endpoints require admin permissions and can execute system-level commands.
						</div>
					</div>
				</div>
			</div>

			<!-- Quotations API Section -->
			<div id="quotations-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:document-outline"></iconify-icon>
							Quotations API
						</h5>
						<p class="mb-16">Manage quotations and price estimates.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/quotations</code> - List quotations (supports search, status, lead_id filters)</li>
							<li><code>GET /api/v1/quotations/{id}</code> - Get quotation details with items</li>
							<li><code>POST /api/v1/quotations</code> - Create a new quotation (requires lead_id, total_amount, items array)</li>
							<li><code>PUT /api/v1/quotations/{id}</code> - Update quotation</li>
							<li><code>DELETE /api/v1/quotations/{id}</code> - Delete quotation</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Invoices API Section -->
			<div id="invoices-api" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:receipt-outline"></iconify-icon>
							Invoices API
						</h5>
						<p class="mb-16">Manage invoices and billing.</p>
						<h6 class="fw-semibold mb-12">Endpoints</h6>
						<ul class="mb-0">
							<li><code>GET /api/v1/invoices</code> - List invoices (filtered by order ownership)</li>
							<li><code>GET /api/v1/invoices/{id}</code> - Get invoice details with items</li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Error Handling Section -->
			<div id="error-handling" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:danger-triangle-outline"></iconify-icon>
							Error Handling
						</h5>
						<p class="mb-16">The API uses standard HTTP response codes to indicate success or failure.</p>
						<h6 class="fw-semibold mb-12">HTTP Status Codes</h6>
						<ul class="mb-0">
							<li><code>200 OK</code> - Request successful</li>
							<li><code>201 Created</code> - Resource created successfully</li>
							<li><code>400 Bad Request</code> - Invalid request parameters</li>
							<li><code>401 Unauthorized</code> - Missing or invalid authentication token</li>
							<li><code>403 Forbidden</code> - Insufficient permissions</li>
							<li><code>404 Not Found</code> - Resource not found</li>
							<li><code>422 Unprocessable Entity</code> - Validation errors</li>
							<li><code>500 Internal Server Error</code> - Server error</li>
						</ul>
						<h6 class="fw-semibold mb-12 mt-16">Error Response Format</h6>
						<pre class="bg-neutral-50 p-16 radius-12 mb-0"><code>{
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}</code></pre>
					</div>
				</div>
			</div>

			<!-- Additional Info Section -->
			<div id="additional-info" class="api-section" style="display: none;">
				<div class="card border-0 mb-24">
					<div class="card-body p-24">
						<h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:info-circle-outline"></iconify-icon>
							Additional Info
						</h5>
						<h6 class="fw-semibold mb-12">Response Format</h6>
						<p class="mb-16">All API responses are in JSON format. Ensure you include <code>Accept: application/json</code> in your request headers.</p>
						<h6 class="fw-semibold mb-12">Rate Limiting</h6>
						<p class="mb-16">API requests are rate-limited to prevent abuse. Rate limit headers are included in all responses.</p>
						<h6 class="fw-semibold mb-12">Pagination</h6>
						<p class="mb-0">List endpoints support pagination using <code>page</code> and <code>per_page</code> query parameters.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Show getting started by default
	showSection('getting-started');
	
	// Handle sidebar clicks
	document.querySelectorAll('[data-section]').forEach(link => {
		link.addEventListener('click', function(e) {
			e.preventDefault();
			const sectionId = this.getAttribute('data-section');
			showSection(sectionId);
			
			// Update active button
			document.querySelectorAll('[data-section]').forEach(btn => {
				btn.classList.remove('btn-primary');
				btn.classList.add('btn-outline-secondary');
			});
			this.classList.remove('btn-outline-secondary');
			this.classList.add('btn-primary');
		});
	});
	
	// Handle hash navigation
	if (window.location.hash) {
		const sectionId = window.location.hash.substring(1);
		showSection(sectionId);
		updateActiveButton(sectionId);
	}
});

function showSection(sectionId) {
	// Hide all sections
	document.querySelectorAll('.api-section').forEach(section => {
		section.style.display = 'none';
	});
	
	// Show selected section
	const section = document.getElementById(sectionId);
	if (section) {
		section.style.display = 'block';
		window.location.hash = sectionId;
		section.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}
}

function updateActiveButton(sectionId) {
	document.querySelectorAll('[data-section]').forEach(btn => {
		if (btn.getAttribute('data-section') === sectionId) {
			btn.classList.remove('btn-outline-secondary');
			btn.classList.add('btn-primary');
		} else {
			btn.classList.remove('btn-primary');
			btn.classList.add('btn-outline-secondary');
		}
	});
}

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
@endsection

