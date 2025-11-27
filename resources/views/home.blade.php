@extends('layouts.app')

@section('title', \App\Models\Setting::getValue('home_meta_title') ?: 'Home')

@section('meta')
    @php
        $metaTitle = \App\Models\Setting::getValue('home_meta_title');
        $metaDescription = \App\Models\Setting::getValue('home_meta_description');
        $metaKeywords = \App\Models\Setting::getValue('home_meta_keywords');
    @endphp
    @if($metaTitle)
        <meta property="og:title" content="{{ $metaTitle }}">
    @endif

    @if($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
        <meta property="og:description" content="{{ $metaDescription }}">
    @endif
    @if($metaKeywords)
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
@endsection

@section('content')
    @if($homeSlides->isNotEmpty())
        <section class="home-hero-slider">
            <div id="homeHeroCarousel" class="carousel slide" data-bs-ride="carousel">
                @if($homeSlides->count() > 1)
                    <div class="carousel-indicators">
                        @foreach($homeSlides as $index => $slide)
                            <button type="button"
                                    data-bs-target="#homeHeroCarousel"
                                    data-bs-slide-to="{{ $index }}"
                                    @class(['active' => $index === 0])
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif

                <div class="carousel-inner">
                    @foreach($homeSlides as $index => $slide)
                        @php
                            $showTitle = $slide->show_title ?? true;
                            $showDescription = $slide->show_description ?? true;
                            $showButton = $slide->show_button ?? true;
                            $buttonSize = $slide->button_size ?? '';
                            $buttonColor = $slide->button_color ?? '';
                            $titlePosition = $slide->title_position ?? 'left';
                            $descriptionPosition = $slide->description_position ?? 'left';
                            $buttonPosition = $slide->button_position ?? 'left';
                            
                            // Calculate position classes
                            $contentAlign = 'text-left';
                            if ($titlePosition === 'center' || $descriptionPosition === 'center' || $buttonPosition === 'center') {
                                $contentAlign = 'text-center';
                            } elseif ($titlePosition === 'right' || $descriptionPosition === 'right' || $buttonPosition === 'right') {
                                $contentAlign = 'text-right';
                            }
                            
                            // Button size classes
                            $buttonSizeClass = '';
                            if ($buttonSize === 'small') $buttonSizeClass = 'btn-sm';
                            elseif ($buttonSize === 'medium') $buttonSizeClass = '';
                            elseif ($buttonSize === 'large') $buttonSizeClass = 'btn-lg';
                            
                            // Make slide clickable if button is hidden but link exists
                            $isClickable = !$showButton && $slide->button_link;
                        @endphp
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }} {{ $isClickable ? 'slider-clickable' : '' }}" 
                             @if($isClickable) data-slider-link="{{ $slide->button_link }}" @endif>
                            <picture>
                                <source media="(max-width: 991px)" srcset="{{ asset('storage/' . $slide->mobile_image_path) }}">
                                <img src="{{ asset('storage/' . $slide->desktop_image_path) }}"
                                     class="d-block w-100 home-hero-slider__image"
                                     alt="{{ $slide->alt_text ?? $slide->title ?? 'Homepage slide' }}"
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                     onerror="this.onerror=null; this.style.display='none'; this.closest('.carousel-item').style.display='none';">
                            </picture>
                            <div class="home-hero-slider__overlay">
                                <div class="container px-4 px-lg-5">
                                    <div class="home-hero-slider__content {{ $contentAlign }}">
                                        @if($showTitle && $slide->title)
                                            <h2 class="display-5 fw-semibold mb-3 text-white slider-title" style="text-align: {{ $titlePosition ?: 'left' }};">{{ $slide->title }}</h2>
                                        @endif
                                        @if($showDescription && $slide->description)
                                            <p class="lead text-white-50 mb-4 slider-description" style="text-align: {{ $descriptionPosition ?: 'left' }};">{{ $slide->description }}</p>
                                        @endif
                                        @if($showButton && $slide->button_text && $slide->button_link)
                                            <div class="slider-button-wrapper" style="text-align: {{ $buttonPosition ?: 'left' }};">
                                                <a href="{{ $slide->button_link }}"
                                                   class="btn btn-primary {{ $buttonSizeClass }} px-4"
                                                   style="{{ $buttonColor ? 'background-color: ' . $buttonColor . '; border-color: ' . $buttonColor . ';' : '' }}">
                                                    {{ $slide->button_text }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($homeSlides->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </section>
    @endif

    @if($homeFeaturedProducts->isNotEmpty())
        <section class="py-10 md:py-16">
            <div class="container">
                <div class="flex items-end justify-between flex-wrap gap-3 mb-8">
                    <div>
                        <h2 class="heading3 mb-2">Featured Products</h2>
                        <p class="caption1 text-secondary mb-0">Handpicked selections curated just for you.</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="button-main bg-white text-black border border-black">View all products</a>
                </div>
                <div class="featured-products-scroll">
                    <div class="featured-products-container">
                        @foreach($homeFeaturedProducts as $product)
                            @php
                                $primaryImage = $product->image;
                                if (!$primaryImage && $product->images->isNotEmpty()) {
                                    $primaryImage = asset('storage/' . $product->images->first()->path);
                                }
                                $primaryImage = $primaryImage ?: asset('shopus/assets/images/homepage-one/product-img/product-img-1.webp');
                            @endphp
                            <div class="featured-product-card">
                                <div class="bg-white rounded-2xl overflow-hidden h-full">
                                    <div class="aspect-[3/4] overflow-hidden">
                                        <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4 flex flex-col gap-2">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <a href="{{ route('products.show', $product->id) }}" class="text-title font-semibold text-black duration-300 hover:text-green">
                                                    {{ $product->name }}
                                                </a>
                                                @if($product->category)
                                                    <p class="caption1 text-secondary mb-0">{{ $product->category->name }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <span class="text-title font-semibold">{{ $product->formatted_effective_price }}</span>
                                                @if($product->has_discount)
                                                    <span class="caption1 text-secondary line-through ml-1">{{ $product->formatted_price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
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
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($secondSlider['should_show'] ?? false)
        <section class="py-10 md:py-16 home-secondary-slider">
            <div class="container">
                <div class="home-secondary-slider__wrapper">
                    @if(!empty($secondSlider['button_link']))
                        <a href="{{ $secondSlider['button_link'] }}"
                           class="stretched-link"
                           aria-label="Explore highlighted collection"></a>
                    @endif
                    <picture>
                        <source media="(max-width: 991px)" srcset="{{ asset('storage/' . $secondSlider['mobile_image_path']) }}">
                        <img src="{{ asset('storage/' . $secondSlider['desktop_image_path']) }}"
                             alt="{{ $secondSlider['alt_text'] ?: 'Featured look' }}"
                             loading="lazy">
                    </picture>
                </div>
            </div>
        </section>
    @endif

    @if($productSlides->isNotEmpty())
        <section class="py-10 md:py-16 home-product-slides">
            <div class="container">
                <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-8">
                    <div>
                        <div class="text-button-uppercase text-green mb-2">Spotlight carousel</div>
                        <h2 class="heading3 mb-2">Handpicked looks to shop now</h2>
                        <p class="caption1 text-secondary mb-0">These products were curated by the team—keep scrolling to explore the edit.</p>
                    </div>
                    <div class="d-inline-flex gap-2">
                        <button type="button" id="productSlidesPrev" class="btn btn-outline-secondary radius-12">
                            <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
                        </button>
                        <button type="button" id="productSlidesNext" class="btn btn-outline-secondary radius-12">
                            <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                        </button>
                    </div>
                </div>
                <div class="product-slides-track-wrapper">
                    <div class="product-slides-track" id="productSlidesTrack">
                        @foreach($productSlides as $product)
                            @php
                                $primaryImage = $product->image;
                                if (!$primaryImage && $product->images->isNotEmpty()) {
                                    $primaryImage = asset('storage/' . $product->images->first()->path);
                                }
                                $primaryImage = $primaryImage ?: asset('shopus/assets/images/homepage-one/product-img/product-img-1.webp');
                            @endphp
                            <div class="product-slide-card">
                                <div class="bg-white rounded-2xl overflow-hidden h-full d-flex flex-column">
                                    <div class="aspect-[3/4] overflow-hidden">
                                        <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4 d-flex flex-column gap-2 flex-grow-1">
                                        <div>
                                            <a href="{{ route('products.show', $product->id) }}" class="text-title font-semibold text-black hover:text-green d-block">
                                                {{ $product->name }}
                                            </a>
                                            @if($product->category)
                                                <p class="caption1 text-secondary mb-0">{{ $product->category->name }}</p>
                                            @endif
                                        </div>
                                        <div class="text-title font-semibold">
                                            {{ $product->formatted_effective_price }}
                                            @if($product->has_discount)
                                                <span class="caption1 text-secondary line-through ms-1">{{ $product->formatted_price }}</span>
                                            @endif
                                        </div>
                                        <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="button-main w-100 flex items-center justify-center gap-2 whitespace-nowrap" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                <iconify-icon icon="solar:bag-3-linear" class="text-xl"></iconify-icon>
                                                <span>Add to bag</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($thirdSlider['should_show'] ?? false)
        <section class="py-10 md:py-16 home-third-slider">
            <div class="container">
                <div class="home-third-slider__wrapper">
                    @if(!empty($thirdSlider['button_link']))
                        <a href="{{ $thirdSlider['button_link'] }}"
                           class="stretched-link"
                           aria-label="Explore highlighted collection"></a>
                    @endif
                    <picture>
                        <source media="(max-width: 991px)" srcset="{{ asset('storage/' . $thirdSlider['mobile_image_path']) }}">
                        <img src="{{ asset('storage/' . $thirdSlider['desktop_image_path']) }}"
                             alt="{{ $thirdSlider['alt_text'] ?: 'Featured look' }}"
                             loading="lazy">
                    </picture>
                </div>
            </div>
        </section>
    @endif

    @if($productSlider2->isNotEmpty())
        <section class="py-10 md:py-16 home-product-slider2">
            <div class="container">
                <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-8">
                    <div>
                        <h2 class="heading3 mb-2">Featured Collection</h2>
                        <p class="caption1 text-secondary mb-0">Handpicked products for you.</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($productSlider2 as $product)
                        @php
                            $primaryImage = $product->image;
                            if (!$primaryImage && $product->images->isNotEmpty()) {
                                $primaryImage = asset('storage/' . $product->images->first()->path);
                            }
                            $primaryImage = $primaryImage ?: asset('shopus/assets/images/homepage-one/product-img/product-img-1.webp');
                        @endphp
                        <div class="bg-white rounded-2xl overflow-hidden h-full d-flex flex-column">
                            <div class="aspect-[3/4] overflow-hidden">
                                <a href="{{ route('products.show', $product->id) }}">
                                    <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </a>
                            </div>
                            <div class="p-4 d-flex flex-column gap-2 flex-grow-1">
                                <div>
                                    <a href="{{ route('products.show', $product->id) }}" class="text-title font-semibold text-black hover:text-green d-block">
                                        {{ $product->name }}
                                    </a>
                                    @if($product->category)
                                        <p class="caption1 text-secondary mb-0">{{ $product->category->name }}</p>
                                    @endif
                                </div>
                                <div class="text-title font-semibold">
                                    {{ $product->formatted_effective_price }}
                                    @if($product->has_discount)
                                        <span class="caption1 text-secondary line-through ms-1">{{ $product->formatted_price }}</span>
                                    @endif
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="button-main w-100 flex items-center justify-center gap-2 whitespace-nowrap" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                        <iconify-icon icon="solar:bag-3-linear" class="text-xl"></iconify-icon>
                                        <span>Add to bag</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($homeReviews->isNotEmpty())
        <section class="py-10 md:py-16 home-reviews-section">
            <div class="container">
                @if($homeReviewsTitle || $homeReviewsDescription)
                    <div class="text-center mb-8">
                        @if($homeReviewsTitle)
                            <h2 class="heading3 mb-3">{{ $homeReviewsTitle }}</h2>
                        @endif
                        @if($homeReviewsDescription)
                            <p class="caption1 text-secondary mb-0">{{ $homeReviewsDescription }}</p>
                        @endif
                    </div>
                @endif
                <div class="reviews-track-wrapper">
                    <div class="reviews-track" id="reviewsTrack">
                        @foreach($homeReviews as $review)
                            <div class="review-card">
                                <div class="review-card-inner">
                                    <div class="review-header">
                                        <div class="review-profile">
                                            @if($review->user)
                                                <x-avatar :user="$review->user" size="md" class="review-avatar" />
                                            @else
                                                <div class="review-avatar rounded-circle d-flex align-items-center justify-content-center text-white font-semibold bg-gray-500" style="width: 48px; height: 48px;">
                                                    {{ strtoupper(substr($review->customer_name ?? 'U', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="review-header-text">
                                            <div class="review-name">{{ $review->customer_name }}</div>
                                            <div class="review-title">
                                                {{ $review->product ? $review->product->name : 'Customer' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-text-wrapper">
                                        <div class="review-quote-bg-icon">
                                            <iconify-icon icon="solar:quote-up-bold"></iconify-icon>
                                        </div>
                                        @if($review->comment)
                                            <p class="review-quote-text">
                                                {{ $review->comment }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= ($review->rating ?? 0))
                                                <iconify-icon icon="solar:star-bold" class="star-filled"></iconify-icon>
                                            @else
                                                <iconify-icon icon="solar:star-outline" class="star-outline"></iconify-icon>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="review-quote-icon-bottom">
                                        <iconify-icon icon="solar:quote-up-bold"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="py-10 md:py-16">
        <div class="container">
            <div class="text-center mb-10">
                <div class="text-button-uppercase text-green mb-3">Queen Pro Boutique</div>
                <h2 class="heading2 text-black mb-4">Effortless luxury, delivered with heart.</h2>
                <p class="body1 text-secondary2 mb-6">Discover curated edits, emerging designers, and everyday essentials tailored for life in the GCC.</p>
                <div class="flex flex-col md:flex-row items-center justify-center gap-3">
                    <a href="{{ route('products.index') }}" class="button-main bg-green text-black hover:bg-white">Shop the Collection</a>
                    <a href="{{ route('about') }}" class="button-main bg-white text-black border border-black">Learn about our story</a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 h-full">
                    <span class="text-button-uppercase bg-green text-black px-3 py-1 rounded-full inline-block mb-4">Concierge care</span>
                    <h3 class="heading6 mb-3">Considered service</h3>
                    <p class="caption1 text-secondary mb-0">Personal stylists, tailored advice, and lightning-fast support from our Dubai lounge.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 h-full">
                    <span class="text-button-uppercase bg-green text-black px-3 py-1 rounded-full inline-block mb-4">Same-day Dubai</span>
                    <h3 class="heading6 mb-3">On your schedule</h3>
                    <p class="caption1 text-secondary mb-0">Complimentary courier delivery and sustainable packaging that protects every piece.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 h-full">
                    <span class="text-button-uppercase bg-green text-black px-3 py-1 rounded-full inline-block mb-4">Members first</span>
                    <h3 class="heading6 mb-3">Rewards that stack</h3>
                    <p class="caption1 text-secondary mb-0">Earn points, unlock private drops, and enjoy early previews of upcoming collaborations.</p>
                </div>
            </div>
        </div>
    </section>

    @if($categories->isNotEmpty())
        <section class="py-10 md:py-16">
            <div class="container">
                <div class="flex items-end justify-between flex-wrap gap-3 mb-8">
                    <div>
                        <h2 class="heading3 mb-2">Browse by mood</h2>
                        <p class="caption1 text-secondary mb-0">Handpicked categories to help you dress the moment—workdays, weekends, or whispered soirées.</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="button-main bg-white text-black border border-black">View all products</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($categories->take(6) as $category)
                        <a href="{{ route('products.category', $category->slug) }}" class="block">
                            <div class="bg-white rounded-2xl overflow-hidden h-full">
                                <div class="aspect-[4/3] overflow-hidden">
                                    <img
                                        src="{{ $category->image_path ?: asset('shopus/assets/images/homepage-one/category-img/dresses.webp') }}"
                                        alt="{{ $category->image_alt_text ?: $category->name }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <h3 class="heading6 mb-0 text-black">{{ $category->name }}</h3>
                                        <span class="caption2 bg-green text-black px-3 py-1 rounded-full">{{ $category->products_count ?? $category->products()->count() }} styles</span>
                                    </div>
                                    <p class="caption1 text-secondary mb-0">Explore silhouettes, textures, and palettes curated to complement your rhythm.</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="py-10 md:py-16">
        <div class="container">
            <div class="flex items-end justify-between flex-wrap gap-3 mb-8">
                <div>
                    <h2 class="heading3 mb-2">Featured this week</h2>
                    <p class="caption1 text-secondary mb-0">Limited runs, capsule collections, and pieces we can't stop talking about.</p>
                </div>
                <a href="{{ route('products.index') }}" class="button-main bg-white text-black border border-black">Shop everything</a>
            </div>
            @if($featuredProducts->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        @php
                            $primaryImage = $product->image;
                            if (!$primaryImage && $product->images->isNotEmpty()) {
                                $primaryImage = asset('storage/' . $product->images->first()->path);
                            }
                            $primaryImage = $primaryImage ?: asset('shopus/assets/images/homepage-one/product-img/product-img-1.webp');
                        @endphp
                        <div>
                            <div class="bg-white rounded-2xl overflow-hidden h-full">
                                <div class="aspect-[3/4] overflow-hidden">
                                    <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-4 flex flex-col gap-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('products.show', $product->id) }}" class="text-title font-semibold text-black duration-300 hover:text-green">
                                                {{ $product->name }}
                                            </a>
                                            @if($product->category)
                                                <p class="caption1 text-secondary mb-0">{{ $product->category->name }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="text-title font-semibold">{{ $product->formatted_effective_price }}</span>
                                            @if($product->has_discount)
                                                <span class="caption1 text-secondary line-through ml-1">{{ $product->formatted_price }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
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
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl p-10 text-center">
                    <h3 class="heading6 mb-3">Fresh drops arriving shortly</h3>
                    <p class="caption1 text-secondary mb-5">We're styling the next edit—check back soon or browse the full collection.</p>
                    <a href="{{ route('products.index') }}" class="button-main">Explore the catalogue</a>
                </div>
            @endif
        </div>
    </section>

    <section class="py-10 md:py-16">
        <div class="container">
            <div class="bg-white rounded-2xl p-6 md:p-10 overflow-hidden relative">
                <div class="flex flex-col lg:flex-row items-center gap-6">
                    <div class="lg:w-7/12">
                        <span class="text-button-uppercase bg-green text-black px-3 py-1 rounded-full inline-block mb-4">Stay in the loop</span>
                        <h2 class="heading3 mb-4">Private sales, styling drops, and members-only perks.</h2>
                        <p class="caption1 text-secondary mb-0">Subscribe for inbox moments you'll actually love—no spam, only beautifully crafted updates.</p>
                    </div>
                    <div class="lg:w-5/12 w-full">
                        <form class="flex flex-col sm:flex-row gap-3">
                            <label for="newsletter-email" class="sr-only">Email address</label>
                            <input type="email" id="newsletter-email" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line flex-1" placeholder="you@example.com" required>
                            <button type="submit" class="button-main whitespace-nowrap">Join</button>
                        </form>
                        <p class="caption2 text-secondary mt-3 mb-0">By joining you agree to our <a href="{{ route('privacy') }}" class="text-black underline">privacy policy</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .featured-products-scroll {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
            padding-bottom: 1rem;
        }

        .featured-products-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .featured-products-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .featured-products-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        .featured-products-scroll::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }

        .featured-products-container {
            display: flex;
            gap: 1rem;
            padding-bottom: 0.5rem;
        }

        .featured-product-card {
            flex: 0 0 auto;
            width: calc(50% - 0.5rem);
            min-width: calc(50% - 0.5rem);
        }

        @media (min-width: 768px) {
            .featured-product-card {
                width: calc(33.333% - 0.67rem);
                min-width: calc(33.333% - 0.67rem);
            }
        }

        @media (min-width: 992px) {
            .featured-product-card {
                width: calc(25% - 0.75rem);
                min-width: calc(25% - 0.75rem);
            }
        }

        .home-secondary-slider__wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
        }

        .home-secondary-slider__wrapper picture,
        .home-secondary-slider__wrapper img {
            display: block;
            width: 100%;
            height: 100%;
        }

        .home-secondary-slider__wrapper img {
            object-fit: cover;
            min-height: 320px;
        }

        .home-third-slider__wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
        }

        .home-third-slider__wrapper picture,
        .home-third-slider__wrapper img {
            display: block;
            width: 100%;
            height: 100%;
        }

        .home-third-slider__wrapper img {
            object-fit: cover;
            min-height: 320px;
        }

        .home-product-slides {
            background-color: #f5f5f0;
        }

        .home-reviews-section {
            background-color: #6b7d5a;
        }

        .home-reviews-section .heading3,
        .home-reviews-section .caption1 {
            color: #ffffff;
        }

        .reviews-track-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 1rem;
        }

        .reviews-track {
            display: flex;
            gap: 1.5rem;
            padding-bottom: 0.5rem;
        }

        .review-card {
            flex: 0 0 auto;
            width: calc(85% - 0.75rem);
            min-width: calc(85% - 0.75rem);
        }

        .review-card-inner {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .review-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .review-profile {
            flex-shrink: 0;
        }

        .review-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #d4af37;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .review-header-text {
            flex: 1;
        }

        .review-name {
            font-weight: 700;
            font-size: 1rem;
            color: #000000;
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .review-title {
            font-size: 0.875rem;
            color: #000000;
            font-weight: 400;
            line-height: 1.2;
        }

        .review-text-wrapper {
            position: relative;
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        .review-quote-bg-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: 0;
        }

        .review-quote-bg-icon iconify-icon {
            font-size: 8rem;
            color: #000000;
        }

        .review-quote-text {
            color: #000000;
            font-size: 0.9rem;
            line-height: 1.6;
            text-align: left;
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .review-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .review-rating .star-filled {
            color: #ff6b35;
            font-size: 1.1rem;
            line-height: 1;
        }

        .review-rating .star-outline {
            color: #ff6b35;
            font-size: 1.1rem;
            line-height: 1;
            opacity: 1;
        }

        .review-quote-icon-bottom {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid #d4af37;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .review-quote-icon-bottom iconify-icon {
            font-size: 1.5rem;
            color: #d4af37;
        }

        .reviews-track::-webkit-scrollbar {
            height: 8px;
        }

        .reviews-track::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .reviews-track::-webkit-scrollbar-track {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        @media (min-width: 768px) {
            .review-card {
                width: calc(50% - 0.75rem);
                min-width: calc(50% - 0.75rem);
            }
        }

        @media (min-width: 992px) {
            .review-card {
                width: calc(33.333% - 1rem);
                min-width: calc(33.333% - 1rem);
            }
        }

        .product-slides-track-wrapper {
            position: relative;
        }

        .product-slides-track {
            display: flex;
            gap: 1.25rem;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding-bottom: 0.5rem;
        }

        .product-slide-card {
            flex: 0 0 auto;
            width: calc(70% - 0.5rem);
            min-width: calc(70% - 0.5rem);
        }

        .product-slides-track::-webkit-scrollbar {
            height: 8px;
        }

        .product-slides-track::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.25);
            border-radius: 4px;
        }

        @media (min-width: 768px) {
            .product-slide-card {
                width: calc(40% - 0.5rem);
                min-width: calc(40% - 0.5rem);
            }
        }

        @media (min-width: 992px) {
            .product-slide-card {
                width: calc(25% - 0.75rem);
                min-width: calc(25% - 0.75rem);
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carouselElement = document.getElementById('homeHeroCarousel');
            if (carouselElement && typeof bootstrap !== 'undefined') {
                // Initialize Bootstrap carousel explicitly
                const carousel = new bootstrap.Carousel(carouselElement, {
                    interval: 5000,
                    ride: 'carousel',
                    wrap: true
                });

                // Reset animations on slide change
                carouselElement.addEventListener('slid.bs.carousel', function(e) {
                    // Get the newly active item
                    const activeItem = e.relatedTarget;
                    const content = activeItem.querySelector('.home-hero-slider__content');
                    
                    if (content) {
                        // Reset animation by removing and re-adding classes
                        content.style.animation = 'none';
                        void content.offsetWidth; // Trigger reflow
                        content.style.animation = null;
                        
                        // Reset child animations
                        const title = content.querySelector('h2');
                        const description = content.querySelector('p');
                        const button = content.querySelector('.btn');
                        
                        [title, description, button].forEach(function(el) {
                            if (el) {
                                el.style.animation = 'none';
                                void el.offsetWidth;
                                el.style.animation = null;
                            }
                        });
                    }
                });
                
                // Handle clickable slider items (when button is hidden)
                const clickableSlides = carouselElement.querySelectorAll('.slider-clickable');
                clickableSlides.forEach(function(slide) {
                    slide.addEventListener('click', function(e) {
                        // Don't trigger if clicking on carousel controls
                        if (e.target.closest('.carousel-control-prev, .carousel-control-next, .carousel-indicators')) {
                            return;
                        }
                        const link = this.getAttribute('data-slider-link');
                        if (link) {
                            window.location.href = link;
                        }
                    });
                    slide.style.cursor = 'pointer';
                });

                // Handle image load errors to prevent infinite loading
                const carouselImages = carouselElement.querySelectorAll('.carousel-item img');
                carouselImages.forEach(function(img) {
                    img.addEventListener('error', function() {
                        const carouselItem = this.closest('.carousel-item');
                        if (carouselItem) {
                            carouselItem.style.display = 'none';
                            // If all slides are hidden, hide the entire carousel section
                            const visibleItems = carouselElement.querySelectorAll('.carousel-item:not([style*="display: none"])');
                            if (visibleItems.length === 0) {
                                carouselElement.closest('.home-hero-slider').style.display = 'none';
                            }
                        }
                    });

                    // Ensure images load properly
                    if (!img.complete || img.naturalHeight === 0) {
                        const imgSrc = img.src;
                        img.src = '';
                        img.src = imgSrc;
                    }
                });
            }

            const productSlidesTrack = document.getElementById('productSlidesTrack');
            if (productSlidesTrack) {
                const prevBtn = document.getElementById('productSlidesPrev');
                const nextBtn = document.getElementById('productSlidesNext');
                const scrollByAmount = () => Math.max(productSlidesTrack.clientWidth * 0.8, 320);

                prevBtn?.addEventListener('click', () => {
                    productSlidesTrack.scrollBy({ left: -scrollByAmount(), behavior: 'smooth' });
                });

                nextBtn?.addEventListener('click', () => {
                    productSlidesTrack.scrollBy({ left: scrollByAmount(), behavior: 'smooth' });
                });
            }
        });
    </script>
    @endpush
@endsection

