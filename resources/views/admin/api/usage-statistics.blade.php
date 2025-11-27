@extends('layouts.admin')

@section('title', 'API Usage Statistics')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">API Usage Statistics</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">API Usage Statistics</li>
		</ul>
	</div>

	<!-- Statistics Cards -->
	<div class="row g-3 mb-24">
		<div class="col-md-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between">
						<div>
							<p class="text-secondary-light text-sm mb-1">Total Tokens</p>
							<h3 class="fw-semibold mb-0">{{ $totalTokens }}</h3>
						</div>
						<div class="w-56-px h-56-px bg-primary-focus rounded-circle d-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:key-outline" class="text-primary-main text-xl"></iconify-icon>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between">
						<div>
							<p class="text-secondary-light text-sm mb-1">Active Tokens</p>
							<h3 class="fw-semibold mb-0">{{ $activeTokens }}</h3>
							<p class="text-xs text-secondary-light mb-0">Used at least once</p>
						</div>
						<div class="w-56-px h-56-px bg-purple-100 rounded-circle d-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:key-minimalistic-2-outline" class="text-purple-600 text-xl"></iconify-icon>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between">
						<div>
							<p class="text-secondary-light text-sm mb-1">Recent Usage</p>
							<h3 class="fw-semibold mb-0">{{ $recentUsage }}</h3>
							<p class="text-xs text-secondary-light mb-0">Last 7 days</p>
						</div>
						<div class="w-56-px h-56-px bg-success-focus rounded-circle d-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:check-circle-bold" class="text-success-main text-xl"></iconify-icon>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mb-24">
		<div class="col-md-6">
			<div class="card border-0">
				<div class="card-body p-24">
					<div class="d-flex align-items-center justify-content-between">
						<div>
							<p class="text-secondary-light text-sm mb-1">Total Webhooks</p>
							<h3 class="fw-semibold mb-0">{{ $totalWebhooks }}</h3>
						</div>
						<div class="w-56-px h-56-px bg-primary-focus rounded-circle d-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:clock-circle-outline" class="text-primary-main text-xl"></iconify-icon>
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
							<p class="text-secondary-light text-sm mb-1">Success Rate</p>
							<h3 class="fw-semibold mb-0">{{ $webhookSuccessRate }}%</h3>
							<p class="text-xs text-secondary-light mb-0">Webhook success</p>
						</div>
						<div class="w-56-px h-56-px bg-warning-focus rounded-circle d-flex align-items-center justify-content-center">
							<iconify-icon icon="solar:chart-2-outline" class="text-warning-main text-xl"></iconify-icon>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Token Usage Table -->
	<div class="row">
		<div class="col-lg-8">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Token Usage</h6>
					<div class="table-responsive">
						<table class="table bordered-table mb-0 align-middle">
							<thead>
								<tr>
									<th>Token Name</th>
									<th>Created At</th>
									<th>Last Used</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								@forelse($tokens as $token)
									<tr>
										<td>
											<h6 class="text-md mb-0 fw-medium">{{ $token->name }}</h6>
										</td>
										<td>
											<span class="text-sm">{{ $token->created_at->format('M d, Y H:i') }}</span>
										</td>
										<td>
											@if($token->last_used_at)
												<span class="text-sm">{{ $token->last_used_at->format('M d, Y H:i') }}</span>
											@else
												<span class="text-secondary-light">Never used</span>
											@endif
										</td>
										<td>
											@if($token->has_usage)
												<span class="px-12 py-4 rounded-pill fw-semibold text-xs bg-success-focus text-success-main">
													Active
												</span>
											@else
												<span class="px-12 py-4 rounded-pill fw-semibold text-xs bg-neutral-200 text-neutral-600">
													Inactive
												</span>
											@endif
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="4" class="text-center py-40">
											<p class="text-secondary-light mb-0">No tokens found</p>
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					@if($tokens->hasPages())
						<div class="mt-16">
							{{ $tokens->links() }}
						</div>
					@endif
				</div>
			</div>
		</div>

		<!-- Quick Actions -->
		<div class="col-lg-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Quick Actions</h6>
					<div class="d-flex flex-column gap-2">
						<a href="{{ route('admin.api-tokens.index') }}" class="btn btn-primary radius-12 justify-content-start">
							<iconify-icon icon="solar:wrench-outline" class="me-2"></iconify-icon>
							Manage Tokens
						</a>
						<a href="{{ route('admin.api.webhooks.index') }}" class="btn btn-warning radius-12 justify-content-start">
							<iconify-icon icon="solar:settings-outline" class="me-2"></iconify-icon>
							Webhook Configuration
						</a>
						<a href="{{ route('admin.api.webhook-logs.index') }}" class="btn btn-primary radius-12 justify-content-start">
							<iconify-icon icon="solar:document-text-outline" class="me-2"></iconify-icon>
							View Webhook Logs
						</a>
						<a href="{{ route('admin.api.test-console') }}" class="btn btn-success radius-12 justify-content-start">
							<iconify-icon icon="solar:code-square-outline" class="me-2"></iconify-icon>
							Test API
						</a>
						<a href="{{ route('admin.api.documentation') }}" class="btn btn-dark radius-12 justify-content-start">
							<iconify-icon icon="solar:book-outline" class="me-2"></iconify-icon>
							API Documentation
						</a>
					</div>
				</div>
			</div>

			<!-- API Information -->
			<div class="card border-0 mt-3">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-12">API Information</h6>
					<div class="mb-8">
						<strong class="text-sm">Base URL:</strong>
						<code class="text-danger text-sm d-block mt-1">{{ url('/api') }}</code>
					</div>
					<div class="mb-8">
						<strong class="text-sm">Authentication:</strong>
						<span class="text-sm d-block mt-1">Bearer Token (Laravel Sanctum)</span>
					</div>
					<div class="mb-8">
						<strong class="text-sm">Response Format:</strong>
						<span class="text-sm d-block mt-1">JSON</span>
					</div>
					<div class="alert alert-info mt-12">
						<iconify-icon icon="solar:info-circle-bold"></iconify-icon>
						<span class="text-sm">All API endpoints require authentication except the login endpoint.</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

