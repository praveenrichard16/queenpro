@extends('layouts.admin')

@section('title', 'Legal Pages')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Legal Pages</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="{{ route('admin.cms.legal-pages.index') }}" class="hover-text-primary">Legal Pages</a>
			</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0">
		<div class="card-body p-24">
			<div class="mb-24">
				<h5 class="fw-semibold mb-2">Manage Legal Pages</h5>
				<p class="text-secondary-light mb-0">Edit your Privacy Policy, Terms and Conditions, and Refund and Return Policy pages.</p>
			</div>

			<div class="row g-4">
				@foreach($types as $type => $label)
					@php
						$page = $pages[$type] ?? null;
						$hasContent = $page && !empty($page->content);
						$lastUpdated = $page && $page->updated_at ? $page->updated_at->format('d M Y, H:i') : 'Never';
					@endphp
					<div class="col-lg-4">
						<div class="card border radius-12 h-100">
							<div class="card-body p-24">
								<div class="d-flex align-items-center gap-3 mb-16">
									<div class="icon-box icon-box-lg radius-12 bg-primary-subtle">
										<iconify-icon icon="solar:document-text-linear" class="text-primary-main"></iconify-icon>
									</div>
									<div>
										<h6 class="fw-semibold mb-0">{{ $label }}</h6>
										@if($hasContent)
											<span class="badge bg-success-subtle text-success small">Has Content</span>
										@else
											<span class="badge bg-warning-subtle text-warning small">Empty</span>
										@endif
									</div>
								</div>
								<p class="text-secondary-light small mb-3">
									<iconify-icon icon="solar:calendar-linear" class="me-1"></iconify-icon>
									Last updated: {{ $lastUpdated }}
								</p>
								<a href="{{ route('admin.cms.legal-pages.edit', $type) }}" class="btn btn-primary w-100 radius-12">
									<iconify-icon icon="solar:pen-linear" class="me-1"></iconify-icon>
									Edit Page
								</a>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection

