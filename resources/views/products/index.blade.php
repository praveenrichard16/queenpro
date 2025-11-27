@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Shop']
        ]"
        eyebrow="The collection"
        title="Find pieces that move with your day."
        description="Use the filters to fine-tune silhouettes, price points, and categories. Every item is in stock and ready to ship from Dubai."
    >
        <a href="{{ route('cart.index') }}" class="button-main bg-white text-black border border-black">View cart</a>
    </x-page-hero>

    <section class="py-10 md:py-16">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl p-6 sticky top-6">
                        <h2 class="heading6 text-black mb-6">Filter &amp; Sort</h2>
                        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col gap-4">
                            <div>
                                <label for="search" class="caption2 text-secondary block mb-2">Search</label>
                                <input type="text" id="search" name="search" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" placeholder="E.g. linen dress" value="{{ request('search') }}">
                            </div>
                            <div>
                                <label for="category" class="caption2 text-secondary block mb-2">Category</label>
                                <select id="category" name="category" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line">
                                    <option value="">All</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="caption2 text-secondary block mb-2">Price range ({{ \App\Services\CurrencyService::code() }})</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" class="caption1 flex-1 h-[52px] pl-4 pr-4 rounded-xl border border-line" name="min_price" placeholder="Min" value="{{ request('min_price') }}" min="0" step="0.01">
                                    <span class="caption1 text-secondary">—</span>
                                    <input type="number" class="caption1 flex-1 h-[52px] pl-4 pr-4 rounded-xl border border-line" name="max_price" placeholder="Max" value="{{ request('max_price') }}" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="sort" class="caption2 text-secondary block mb-2">Sort</label>
                                    <select id="sort" name="sort" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line">
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="order" class="caption2 text-secondary block mb-2">Order</label>
                                    <select id="order" name="order" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line">
                                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Asc</option>
                                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Desc</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3">
                                <button type="submit" class="button-main">Apply filters</button>
                                <a href="{{ route('products.index') }}" class="button-main bg-white text-black border border-black text-center">Clear all</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="lg:col-span-9">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-3 mb-6">
                        <div>
                            <h2 class="heading6 text-black mb-1">
                                Showing {{ $products->firstItem() ?? 0 }} – {{ $products->lastItem() ?? 0 }}
                            </h2>
                            <p class="caption2 text-secondary mb-0">{{ $products->total() }} products available</p>
                        </div>
                        <span class="caption2 bg-green text-black px-3 py-1 rounded-full">Secure checkout · Same-day Dubai delivery</span>
                    </div>

                    @if($products->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div>
                                    <div class="bg-white rounded-2xl overflow-hidden h-full flex flex-col">
                                        <a href="{{ route('products.show', $product->id) }}" class="aspect-[3/4] overflow-hidden block">
                                            <img src="{{ $product->image ? asset($product->image) : asset('shopus/assets/images/homepage-one/product-img/product-img-2.webp') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        </a>
                                        <div class="p-4 flex flex-col gap-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <a href="{{ route('products.show', $product->id) }}" class="text-title font-semibold text-black duration-300 hover:text-green block">
                                                        {{ $product->name }}
                                                    </a>
                                                    @if($product->category)
                                                        <span class="caption1 text-secondary">{{ $product->category->name }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-title font-semibold">
                                                    {{ $product->formatted_effective_price }}
                                                    @if($product->has_discount)
                                                        <span class="caption1 text-secondary line-through ml-1">{{ $product->formatted_price }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <span class="caption2 text-secondary">
                                                {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Sold out' }}
                                            </span>
                                            <div class="mt-auto">
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="button-main w-full flex items-center justify-center gap-2 whitespace-nowrap" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                        <iconify-icon icon="solar:bag-heart-linear" class="text-xl"></iconify-icon>
                                                        <span>Add to bag</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-center mt-8">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl p-10 text-center">
                            <h3 class="heading6 mb-3 text-black">Nothing matched those filters</h3>
                            <p class="caption1 text-secondary mb-5">Reset the filters or explore our editor's picks for fresh inspiration.</p>
                            <a href="{{ route('products.index') }}" class="button-main">Reset filters</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
