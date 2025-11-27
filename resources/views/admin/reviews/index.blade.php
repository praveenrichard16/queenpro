@extends('layouts.admin')

@php
	use Illuminate\Support\Str;
@endphp

@section('title', 'Product Reviews')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Product Reviews</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Reviews</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="{{ request('search') }}" placeholder="Customer name, email">
					</div>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All reviews</option>
						<option value="approved" @selected(request('status') === 'approved')>Approved</option>
						<option value="pending" @selected(request('status') === 'pending')>Pending</option>
					</select>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Product</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="product_id">
						<option value="">All products</option>
						@foreach($products as $product)
							<option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Product</th>
							<th>Customer</th>
							<th>Rating</th>
							<th>Review</th>
							<th>Status</th>
							<th>Date</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($reviews as $review)
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium">{{ $review->product->name }}</h6>
								</td>
								<td>
									<div>
										<h6 class="text-sm mb-1 fw-medium">{{ $review->customer_name }}</h6>
										<p class="text-xs text-secondary-light mb-0">{{ $review->customer_email }}</p>
									</div>
								</td>
								<td>
									<div class="d-flex align-items-center gap-1">
										@for($i = 1; $i <= 5; $i++)
											<iconify-icon icon="solar:star-bold" class="{{ $i <= $review->rating ? 'text-warning' : 'text-secondary-light' }}"></iconify-icon>
										@endfor
										<span class="ms-2 fw-semibold">{{ $review->rating }}</span>
									</div>
								</td>
								<td>
									<div>
										@if($review->title)
											<h6 class="text-sm mb-1 fw-medium">{{ $review->title }}</h6>
										@endif
										<p class="text-sm text-secondary-light mb-0">{{ Str::limit($review->comment, 100) }}</p>
									</div>
								</td>
								<td>
									@if($review->is_approved)
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-success-focus text-success-main">Approved</span>
									@else
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-warning-focus text-warning-main">Pending</span>
									@endif
									@if($review->is_featured)
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-primary-focus text-primary-main ms-2">Featured</span>
									@endif
								</td>
								<td>
									<span class="text-sm text-secondary-light">{{ $review->created_at->format('M d, Y') }}</span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										@if(!$review->is_approved)
											<form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
												@csrf
												<button type="submit" class="w-36-px h-36-px bg-success-focus text-success-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Approve">
													<iconify-icon icon="solar:check-circle-bold"></iconify-icon>
												</button>
											</form>
										@else
											<form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline">
												@csrf
												<button type="submit" class="w-36-px h-36-px bg-warning-focus text-warning-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Reject">
													<iconify-icon icon="solar:close-circle-bold"></iconify-icon>
												</button>
											</form>
										@endif
										<form action="{{ route('admin.reviews.feature', $review) }}" method="POST" class="d-inline">
											@csrf
											<button type="submit" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="{{ $review->is_featured ? 'Unfeature' : 'Feature' }}">
												<iconify-icon icon="solar:star-bold"></iconify-icon>
											</button>
										</form>
										<form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')" class="d-inline">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Delete">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No reviews" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No reviews found</h6>
									<p class="text-secondary-light mb-0">Product reviews will appear here.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($reviews->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $reviews->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection

