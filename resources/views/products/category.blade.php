@extends('layouts.app')

@section('title', $category->meta_title ?: $category->name)

@section('meta_description', $category->meta_description)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h2 class="mb-1">{{ $category->name }}</h2>
		@if($category->description)
			<p class="text-secondary mb-0">{{ $category->description }}</p>
		@endif
	</div>
	<a href="{{ route('products.index') }}" class="btn btn-outline-secondary">All Products</a>
</div>

@if($products->count() > 0)
	<div class="row">
		@foreach($products as $product)
			<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
				<div class="card h-100 shadow-sm">
					@if($product->image)
						<img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
					@else
						<img src="https://via.placeholder.com/300x200/6c757d/ffffff?text=No+Image" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
					@endif
					<div class="card-body d-flex flex-column">
						<h6 class="card-title">{{ $product->name }}</h6>
						<p class="card-text text-muted small mb-3">
							<span class="fw-semibold">{{ $product->formatted_effective_price }}</span>
							@if($product->has_discount)
								<span class="text-decoration-line-through ms-1">{{ $product->formatted_price }}</span>
							@endif
						</p>
						<form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
							@csrf
							<input type="hidden" name="product_id" value="{{ $product->id }}">
							<input type="hidden" name="quantity" value="1">
							<button type="submit" class="button-main w-100 d-flex align-items-center justify-content-center gap-2 text-center text-nowrap" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
								<iconify-icon icon="solar:bag-heart-linear" class="text-xl"></iconify-icon>
								<span>Add to bag</span>
							</button>
						</form>
					</div>
				</div>
			</div>
		@endforeach
	</div>
	<div class="d-flex justify-content-center">
		{{ $products->links() }}
	</div>
@else
	<div class="text-center py-5">
		<i class="fas fa-search fa-3x text-muted mb-3"></i>
		<h4>No products found in this category</h4>
		<a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Browse All Products</a>
	</div>
@endif
@endsection
