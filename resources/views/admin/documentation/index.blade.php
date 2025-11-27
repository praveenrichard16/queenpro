@extends('layouts.admin')

@section('title', 'Documentation')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Documentation</h6>
			<p class="text-secondary-light mb-0">Setup guides and documentation for all features.</p>
		</div>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Documentation</li>
		</ul>
	</div>

	<div class="row g-4">
		@forelse($documents as $document)
			<div class="col-lg-4 col-md-6">
				<div class="card border-0 bg-base h-100">
					<div class="card-body p-24">
						<div class="d-flex align-items-start gap-3 mb-16">
							<div class="w-48-px h-48-px rounded-circle bg-primary-focus d-flex align-items-center justify-content-center flex-shrink-0">
								<iconify-icon icon="solar:document-text-outline" class="text-primary-main text-xl"></iconify-icon>
							</div>
							<div class="flex-grow-1">
								<h6 class="fw-semibold mb-2">{{ $document['title'] }}</h6>
								<p class="text-secondary-light mb-0 text-sm">Setup guide and documentation</p>
							</div>
						</div>
						<a href="{{ route('admin.documentation.show', ['doc' => $document['name']]) }}" class="btn btn-primary radius-12 w-100">
							<iconify-icon icon="solar:eye-outline"></iconify-icon>
							View Documentation
						</a>
					</div>
				</div>
			</div>
		@empty
			<div class="col-12">
				<div class="card border-0 bg-base">
					<div class="card-body p-24 text-center">
						<iconify-icon icon="solar:document-text-outline" class="text-4xl text-secondary-light mb-3"></iconify-icon>
						<p class="text-secondary-light mb-0">No documentation files found.</p>
					</div>
				</div>
			</div>
		@endforelse
	</div>
</div>
@endsection

