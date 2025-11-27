@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Products</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Products</li>
		</ul>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif
	@if(session('error'))
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			{{ session('error') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-xl-4 col-lg-6">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y">
							<iconify-icon icon="mage:search"></iconify-icon>
						</span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Search name or description" name="search" value="{{ request('search') }}">
					</div>
				</div>
				<div class="col-xl-3 col-lg-6">
					<label class="form-label text-secondary-light">Category</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="category">
						<option value="">All Categories</option>
						@foreach($categories as $cat)
							<option value="{{ $cat->id }}" @selected(request('category')==$cat->id)>{{ $cat->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xl-3 col-lg-6">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">Any Status</option>
						<option value="1" @selected(request('status')==='1')>Active</option>
						<option value="0" @selected(request('status')==='0')>Inactive</option>
					</select>
				</div>
				<div class="col-xl-2 col-lg-6 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Apply</button>
					<a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
			</form>

			<div class="d-flex flex-wrap gap-2 mt-24 justify-content-between">
				<div class="d-flex gap-2 align-items-center">
					<a href="{{ route('admin.products.export') }}" class="btn btn-outline-secondary radius-12 d-inline-flex align-items-center gap-2">
						<iconify-icon icon="solar:download-linear" class="text-lg"></iconify-icon>
						Export CSV
					</a>
					<form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data" class="d-inline-flex align-items-center">
						@csrf
						<label class="btn btn-outline-secondary radius-12 mb-0 d-flex align-items-center gap-2">
							<iconify-icon icon="solar:upload-minimalistic-linear" class="text-lg"></iconify-icon>
							Import CSV
							<input type="file" name="file" accept=".csv,text/csv" class="d-none" onchange="this.form.submit()">
						</label>
					</form>
				</div>
				<a href="{{ route('admin.products.create') }}" class="btn btn-warning radius-12 text-white d-inline-flex align-items-center gap-2">
					<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					Add Product
				</a>
			</div>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th scope="col">Product</th>
							<th scope="col">Category</th>
							<th scope="col" class="text-end">Product Cost</th>
							<th scope="col" class="text-end">Selling Cost</th>
							<th scope="col">Status</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($products as $product)
							<tr>
								<td>
									<h6 class="text-md fw-medium mb-4">{{ $product->name }}</h6>
									<span class="text-sm text-secondary-light">SKU: {{ $product->sku ?? $product->id }}</span>
								</td>
								<td><span class="text-sm fw-medium text-secondary-light">{{ $product->category->name ?? '-' }}</span></td>
								<td class="text-end">{{ \App\Services\CurrencyService::format($product->price) }}</td>
								<td class="text-end">{{ \App\Services\CurrencyService::format($product->effective_price) }}</td>
								<td>
									<div class="d-flex flex-column gap-1">
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm {{ $product->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600' }}">
											{{ $product->is_active ? 'Active' : 'Inactive' }}
										</span>
										@if($product->is_featured)
											<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-warning-focus text-warning-main">
												<iconify-icon icon="solar:star-bold" class="icon text-xs"></iconify-icon> Featured
											</span>
										@endif
									</div>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="{{ route('admin.products.edit', $product) }}" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
											@csrf
											@method('DELETE')
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center py-80">
									<img src="{{ asset('wowdash/assets/images/notification/empty-message-icon1.png') }}" alt="No data" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No products found</h6>
									<p class="text-secondary-light mb-0">Try adjusting your filters or add a new product.</p>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			@if($products->hasPages())
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					{{ $products->links() }}
				</div>
			@endif
		</div>
	</div>
</div>
@endsection
