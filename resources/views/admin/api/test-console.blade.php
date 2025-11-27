@extends('layouts.admin')

@section('title', 'API Testing Console')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">API Testing Console</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">API Testing Console</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form id="api-test-form">
				<!-- Base URL -->
				<div class="mb-24">
					<label class="form-label fw-semibold mb-2">Base URL</label>
					<input type="text" id="base-url" class="form-control bg-neutral-50 radius-12 h-56-px" value="{{ url('/api/v1') }}" readonly>
					<small class="text-secondary-light">All API requests will be made to this base URL.</small>
				</div>

				<div class="row g-4 mb-24">
					<!-- Method -->
					<div class="col-md-3">
						<label class="form-label fw-semibold mb-2">Method</label>
						<select id="method" class="form-control bg-neutral-50 radius-12 h-56-px">
							<option value="GET">GET</option>
							<option value="POST">POST</option>
							<option value="PUT">PUT</option>
							<option value="PATCH">PATCH</option>
							<option value="DELETE">DELETE</option>
						</select>
					</div>

					<!-- Endpoint -->
					<div class="col-md-9">
						<label class="form-label fw-semibold mb-2">Endpoint</label>
						<input type="text" id="endpoint" class="form-control bg-neutral-50 radius-12 h-56-px" placeholder="/products" value="/products">
						<small class="text-secondary-light">Enter the endpoint path (e.g., /products, /orders, /leads, /me, etc.)</small>
					</div>
				</div>

				<!-- Authorization Token -->
				<div class="mb-24">
					<label class="form-label fw-semibold mb-2">Authorization Token</label>
					<textarea id="token" class="form-control bg-neutral-50 radius-12" rows="2" placeholder="Bearer token here..."></textarea>
					<small class="text-secondary-light">Enter your API token (without 'Bearer' prefix)</small>
				</div>

				<!-- Headers -->
				<div class="mb-24">
					<label class="form-label fw-semibold mb-2">Headers (JSON)</label>
					<textarea id="headers" class="form-control bg-neutral-50 radius-12 font-monospace" rows="4">{"Content-Type": "application/json"}</textarea>
					<small class="text-secondary-light">Optional: Additional headers in JSON format</small>
				</div>

				<!-- Request Body -->
				<div class="mb-24">
					<label class="form-label fw-semibold mb-2">Request Body (JSON)</label>
					<textarea id="body" class="form-control bg-neutral-50 radius-12 font-monospace" rows="6">{"key": "value"}</textarea>
					<small class="text-secondary-light">Required for POST, PUT, PATCH requests</small>
				</div>

				<!-- Action Buttons -->
				<div class="d-flex gap-2 mb-24">
					<button type="button" id="send-request" class="btn btn-primary radius-12 px-24">
						<iconify-icon icon="solar:play-outline" class="me-2"></iconify-icon>
						Send Request
					</button>
					<button type="button" id="clear-request" class="btn btn-outline-secondary radius-12 px-24">
						<iconify-icon icon="solar:refresh-outline" class="me-2"></iconify-icon>
						Clear
					</button>
				</div>

				<!-- Response Section -->
				<div class="mb-24">
					<label class="form-label fw-semibold mb-2">Response</label>
					<div id="response-loading" class="d-none text-center py-40">
						<div class="spinner-border text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</div>
					<div id="response-container" class="border radius-12 p-16 bg-neutral-50 min-h-200">
						<p class="text-secondary-light text-center mb-0">Response will appear here after sending a request</p>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Example Requests -->
	<div class="card border-0 mt-3">
		<div class="card-body p-24">
			<h6 class="fw-semibold mb-16">Example Requests</h6>
			<div class="row g-3">
				<div class="col-md-6">
					<div class="border radius-12 p-16">
						<h6 class="fw-semibold mb-2">Get Current User</h6>
						<p class="text-sm text-secondary-light mb-2">GET /me</p>
						<button type="button" class="btn btn-sm btn-primary radius-12" onclick="loadExample('GET', '/me', '', '{}')">
							<iconify-icon icon="solar:play-outline" class="me-1"></iconify-icon>
							Load Example
						</button>
					</div>
				</div>
				<div class="col-md-6">
					<div class="border radius-12 p-16">
						<h6 class="fw-semibold mb-2">List Products</h6>
						<p class="text-sm text-secondary-light mb-2">GET /products</p>
						<button type="button" class="btn btn-sm btn-primary radius-12" onclick="loadExample('GET', '/products', '', '{}')">
							<iconify-icon icon="solar:play-outline" class="me-1"></iconify-icon>
							Load Example
						</button>
					</div>
				</div>
				<div class="col-md-6">
					<div class="border radius-12 p-16">
						<h6 class="fw-semibold mb-2">Create Lead</h6>
						<p class="text-sm text-secondary-light mb-2">POST /leads</p>
						<button type="button" class="btn btn-sm btn-primary radius-12" onclick="loadExample('POST', '/leads', '', JSON.stringify({'name': 'John Doe', 'email': 'john@example.com', 'phone': '1234567890'}, null, 2))">
							<iconify-icon icon="solar:play-outline" class="me-1"></iconify-icon>
							Load Example
						</button>
					</div>
				</div>
				<div class="col-md-6">
					<div class="border radius-12 p-16">
						<h6 class="fw-semibold mb-2">List Orders</h6>
						<p class="text-sm text-secondary-light mb-2">GET /orders</p>
						<button type="button" class="btn btn-sm btn-primary radius-12" onclick="loadExample('GET', '/orders', '', '{}')">
							<iconify-icon icon="solar:play-outline" class="me-1"></iconify-icon>
							Load Example
						</button>
					</div>
				</div>
				<div class="col-md-6">
					<div class="border radius-12 p-16">
						<h6 class="fw-semibold mb-2">Create Review</h6>
						<p class="text-sm text-secondary-light mb-2">POST /reviews</p>
						<button type="button" class="btn btn-sm btn-primary radius-12" onclick="loadExample('POST', '/reviews', '', JSON.stringify({'product_id': 1, 'rating': 5, 'comment': 'Great product!'}, null, 2))">
							<iconify-icon icon="solar:play-outline" class="me-1"></iconify-icon>
							Load Example
						</button>
					</div>
				</div>
				<div class="col-md-6">
					<div class="border radius-12 p-16">
						<h6 class="fw-semibold mb-2">Validate Coupon</h6>
						<p class="text-sm text-secondary-light mb-2">POST /coupons/validate</p>
						<button type="button" class="btn btn-sm btn-primary radius-12" onclick="loadExample('POST', '/coupons/validate', '', JSON.stringify({'code': 'DISCOUNT10', 'amount': 100}, null, 2))">
							<iconify-icon icon="solar:play-outline" class="me-1"></iconify-icon>
							Load Example
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const form = document.getElementById('api-test-form');
	const sendButton = document.getElementById('send-request');
	const clearButton = document.getElementById('clear-request');
	const responseContainer = document.getElementById('response-container');
	const responseLoading = document.getElementById('response-loading');

	sendButton.addEventListener('click', async function() {
		const baseUrl = document.getElementById('base-url').value;
		const method = document.getElementById('method').value;
		const endpoint = document.getElementById('endpoint').value;
		const token = document.getElementById('token').value.trim();
		const headersText = document.getElementById('headers').value.trim();
		const bodyText = document.getElementById('body').value.trim();

		// Show loading
		responseLoading.classList.remove('d-none');
		responseContainer.innerHTML = '';
		responseContainer.style.display = 'none';

		try {
			// Parse headers
			let headers = {};
			if (headersText) {
				headers = JSON.parse(headersText);
			}
			headers['Accept'] = 'application/json';
			headers['Content-Type'] = 'application/json';
			
			if (token) {
				headers['Authorization'] = 'Bearer ' + token;
			}

			// Prepare fetch options
			const options = {
				method: method,
				headers: headers,
			};

			// Add body for methods that support it
			if (['POST', 'PUT', 'PATCH'].includes(method) && bodyText) {
				options.body = bodyText;
			}

			// Make the request
			const url = baseUrl + endpoint;
			const response = await fetch(url, options);
			const responseText = await response.text();
			
			let responseData;
			try {
				responseData = JSON.parse(responseText);
			} catch {
				responseData = responseText;
			}

			// Display response
			responseLoading.classList.add('d-none');
			responseContainer.style.display = 'block';
			
			const statusClass = response.ok ? 'text-success' : 'text-danger';
			responseContainer.innerHTML = `
				<div class="mb-3">
					<strong class="me-2">Status:</strong>
					<span class="${statusClass}">${response.status} ${response.statusText}</span>
				</div>
				<pre class="bg-white p-16 radius-12 mb-0" style="max-height: 500px; overflow: auto;">${typeof responseData === 'string' ? responseData : JSON.stringify(responseData, null, 2)}</pre>
			`;
		} catch (error) {
			responseLoading.classList.add('d-none');
			responseContainer.style.display = 'block';
			responseContainer.innerHTML = `
				<div class="alert alert-danger mb-0">
					<strong>Error:</strong> ${error.message}
				</div>
			`;
		}
	});

	clearButton.addEventListener('click', function() {
		document.getElementById('endpoint').value = '';
		document.getElementById('token').value = '';
		document.getElementById('headers').value = '{"Content-Type": "application/json"}';
		document.getElementById('body').value = '{"key": "value"}';
		responseContainer.innerHTML = '<p class="text-secondary-light text-center mb-0">Response will appear here after sending a request</p>';
		responseContainer.style.display = 'block';
	});
});

function loadExample(method, endpoint, token, body) {
	document.getElementById('method').value = method;
	document.getElementById('endpoint').value = endpoint;
	if (token) {
		document.getElementById('token').value = token;
	}
	if (body && body !== '{}') {
		document.getElementById('body').value = body;
	}
}
</script>
@endsection

