@extends('layouts.app')

@section('title', $product->name)

@php
    $categoryBreadcrumbs = [];
    if ($product->category) {
        $categoryBreadcrumbs[] = [
            'label' => $product->category->name,
            'url' => route('products.category', $product->category->slug),
        ];
    }

    $breadcrumbs = array_merge([
        ['label' => 'Home', 'url' => route('home')],
    ], $categoryBreadcrumbs, [
        ['label' => $product->name],
    ]);

    $galleryImages = collect(
        $product->images->map(fn($image) => asset($image->path))->prepend(
            $product->image ? asset($product->image) : asset('shopus/assets/images/homepage-one/product-img/product-img-5.webp')
        )
    )->unique();

    // Get color and size attributes
    $colorAttribute = $product->attributes->firstWhere('name', 'Color') ?? $product->attributes->firstWhere('slug', 'color');
    $sizeAttribute = $product->attributes->firstWhere('name', 'Size') ?? $product->attributes->firstWhere('slug', 'size');
    
    // Calculate discount percentage
    $discountPercent = $product->has_discount ? round((($product->price - $product->selling_price) / $product->price) * 100) : 0;

    // Calculate rating breakdown for reviews
    $ratingBreakdown = [];
    for ($i = 5; $i >= 1; $i--) {
        $count = $reviews->where('rating', $i)->count();
        $percentage = $reviews->count() > 0 ? round(($count / $reviews->count()) * 100) : 0;
        $ratingBreakdown[$i] = ['count' => $count, 'percentage' => $percentage];
    }
@endphp

@section('content')
    <x-page-hero
        :breadcrumbs="$breadcrumbs"
        :eyebrow="$product->category?->name ?? 'Product detail'"
        :title="$product->name"
        description="Crafted with premium materials and delivered with the care you expect from WowDash."
    />

    <div class="product-detail variable">
        <div class="featured-product variable underwear md:py-20 py-14">
            <div class="container flex justify-between gap-y-6 flex-wrap">
                <div class="list-img md:w-1/2 md:pr-[45px] w-full flex-shrink-0">
                    <div class="flex gap-4">
                        <!-- Thumbnail Gallery (Vertical Scrollable) -->
                        @if($galleryImages->count() > 1)
                            <div class="gallery-thumbnails flex-shrink-0 w-20 md:w-24">
                                <div class="gallery-thumbnails-scroll overflow-y-auto max-h-[600px] pr-2" style="scrollbar-width: thin;">
                                    @foreach($galleryImages as $index => $image)
                                        <div class="gallery-thumbnail cursor-pointer border-2 rounded-lg overflow-hidden transition-all mb-3 {{ $index === 0 ? 'border-black' : 'border-transparent hover:border-gray-300' }}" 
                                             data-image-index="{{ $index }}"
                                             onclick="changeMainImage({{ $index }})">
                                            <img src="{{ $image }}" alt="{{ $product->name }} - Thumbnail {{ $index + 1 }}" 
                                                 class="w-full aspect-square object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Main Image Display -->
                        <div class="main-image-container flex-1">
                            <div class="main-image-wrapper relative bg-white rounded-2xl p-2 border border-gray-200 shadow-md">
                                <div class="popup-link cursor-pointer rounded-xl overflow-hidden">
                                    <img id="mainProductImage" src="{{ $galleryImages->first() }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full aspect-square object-cover rounded-xl"
                                         style="max-width: 1080px; max-height: 1080px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fullscreen Popup -->
                    <div class="swiper popup-img fixed inset-0 bg-black bg-opacity-95 z-50 hidden items-center justify-center" style="display: none;">
                        <span class="close-popup-btn absolute top-4 right-4 z-[2] cursor-pointer">
                            <i class="ph ph-x text-3xl text-white"></i>
                        </span>
                        <div class="swiper-wrapper">
                            @foreach($galleryImages as $image)
                                <div class="swiper-slide flex items-center justify-center">
                                    <img src="{{ $image }}" alt="{{ $product->name }}" class="max-w-full max-h-screen object-contain">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-prev text-white"></div>
                        <div class="swiper-button-next text-white"></div>
                    </div>
                </div>
                <div class="product-infor md:w-1/2 w-full lg:pl-[15px] md:pl-2">
                    <div class="sticky top-20 bg-white rounded-2xl p-6 md:p-8 border border-gray-200 shadow-sm">
                        <div class="flex justify-between">
                            <div>
                                <div class="product-category caption2 text-secondary font-semibold uppercase">{{ $product->category?->name ?? 'Product' }}</div>
                                <div class="product-name heading4 mt-1">{{ $product->name }}</div>
                            </div>
                            <div class="add-wishlist-btn w-10 h-10 flex-shrink-0 flex items-center justify-center border border-line cursor-pointer rounded-lg duration-300 hover:bg-black hover:text-white">
                                <i class="ph ph-heart text-xl"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mt-3">
                            <div class="rate flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="ph-fill ph-star text-sm {{ $i <= round($product->average_rating) ? 'text-yellow' : 'text-secondary' }}"></i>
                                @endfor
                            </div>
                            <span class="caption1 text-secondary">({{ number_format($product->total_reviews, 0) }} {{ Str::plural('review', $product->total_reviews) }})</span>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap mt-5 pb-6 border-b-2 border-gray-200">
                            <div class="product-price heading5 text-black font-bold">{{ $product->formatted_effective_price }}</div>
                            @if($product->has_discount)
                                <div class="w-px h-4 bg-gray-300"></div>
                                <div class="product-origin-price font-normal text-gray-500">
                                    <del>{{ $product->formatted_price }}</del>
                                </div>
                                <div class="product-sale caption2 font-semibold bg-green text-white px-3 py-1 inline-block rounded-full shadow-sm">-{{ $discountPercent }}%</div>
                            @endif
                        </div>
                        @if($product->short_description)
                            <div class="product-description text-secondary mt-4 p-4 bg-gray-50 rounded-lg border border-gray-100">{{ $product->short_description }}</div>
                        @endif
                        
                        @php
                            $showTimer = false;
                            $timerEnd = null;
                            if ($product->enable_countdown_timer) {
                                if ($product->countdown_timer_end) {
                                    $timerEnd = \Carbon\Carbon::parse($product->countdown_timer_end);
                                    $showTimer = $timerEnd->isFuture();
                                }
                            }
                        @endphp
                        
                        {{-- Debug: Remove this after testing --}}
                        @if(config('app.debug') && $product->enable_countdown_timer)
                            <div class="mt-4 p-3 bg-yellow-100 border border-yellow-400 rounded text-sm">
                                <strong>Timer Debug:</strong><br>
                                Enable Timer: {{ $product->enable_countdown_timer ? 'Yes' : 'No' }}<br>
                                Timer End: {{ $product->countdown_timer_end ? $product->countdown_timer_end : 'Not Set' }}<br>
                                @if($timerEnd)
                                    Parsed End: {{ $timerEnd->format('Y-m-d H:i:s') }}<br>
                                    Is Future: {{ $showTimer ? 'Yes' : 'No' }}<br>
                                    Current Time: {{ now()->format('Y-m-d H:i:s') }}
                                @endif
                            </div>
                        @endif
                        
                        @if($showTimer && $timerEnd)
                            <div class="countdown-block flex items-center justify-between flex-wrap gap-y-4 mt-6 p-4 bg-red-50 rounded-lg border-2 border-red">
                                <div class="text-title">
                                    Hurry Up!<br />
                                    Offer ends in:
                                </div>
                                <div class="countdown-time flex items-center lg:gap-5 gap-3 max-[400px]:justify-between max-[400px]:w-full" data-end-time="{{ $timerEnd->timestamp }}">
                                    <div class="item w-[60px] h-[60px] flex flex-col items-center justify-center border border-red rounded-lg bg-white">
                                        <div class="days heading6 text-center">00</div>
                                        <div class="caption1 text-center">Days</div>
                                    </div>
                                    <div class="heading5">:</div>
                                    <div class="item w-[60px] h-[60px] flex flex-col items-center justify-center border border-red rounded-lg bg-white">
                                        <div class="hours heading6 text-center">00</div>
                                        <div class="caption1 text-center">Hours</div>
                                    </div>
                                    <div class="heading5">:</div>
                                    <div class="item w-[60px] h-[60px] flex flex-col items-center justify-center border border-red rounded-lg bg-white">
                                        <div class="mins heading6 text-center">00</div>
                                        <div class="caption1 text-center">Mins</div>
                                    </div>
                                    <div class="heading5">:</div>
                                    <div class="item w-[60px] h-[60px] flex flex-col items-center justify-center border border-red rounded-lg bg-white">
                                        <div class="secs heading6 text-center">00</div>
                                        <div class="caption1 text-center">Secs</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="list-action mt-6">
                            @if($colorAttribute && $colorAttribute->values->isNotEmpty())
                                <div class="choose-color mt-5">
                                    <div class="text-title">Colors: <span class="text-title color-selected"></span></div>
                                    <div class="list-color flex items-center gap-2 flex-wrap mt-3">
                                        @foreach($colorAttribute->values as $colorValue)
                                            <div class="color-item w-12 h-12 rounded-xl duration-300 relative cursor-pointer border-2 border-transparent hover:border-black" 
                                                 data-color="{{ $colorValue->value }}"
                                                 style="background-color: {{ $colorValue->color_code ?? '#ccc' }};"
                                                 title="{{ $colorValue->value }}">
                                                @if($colorValue->image_path)
                                                    <img src="{{ asset($colorValue->image_path) }}" alt="{{ $colorValue->value }}" class="rounded-xl w-full h-full object-cover">
                                                @endif
                                                <div class="tag-action bg-black text-white caption2 capitalize px-1.5 py-0.5 rounded-sm absolute -bottom-8 left-1/2 -translate-x-1/2 opacity-0 pointer-events-none whitespace-nowrap">
                                                    {{ $colorValue->value }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($sizeAttribute && $sizeAttribute->values->isNotEmpty())
                                <div class="choose-size mt-5">
                                    <div class="heading flex items-center justify-between">
                                        <div class="text-title">Size: <span class="text-title size-selected"></span></div>
                                        <div class="caption1 size-guide text-red underline cursor-pointer">Size Guide</div>
                                    </div>
                                    <div class="list-size flex items-center gap-2 flex-wrap mt-3">
                                        @foreach($sizeAttribute->values as $sizeValue)
                                            <div class="size-item w-12 h-12 flex items-center justify-center text-button rounded-full bg-white border border-line cursor-pointer duration-300 hover:bg-black hover:text-white hover:border-black {{ strtolower($sizeValue->value) === 'freesize' ? 'px-3 py-2' : '' }}" 
                                                 data-size="{{ $sizeValue->value }}">
                                                {{ $sizeValue->value }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="text-title mt-5">Quantity:</div>
                            <div class="choose-quantity flex items-center max-xl:flex-wrap lg:justify-between gap-5 mt-3">
                                <div class="quantity-block md:p-2 max-md:py-1.5 max-md:px-2 flex items-center justify-between rounded-lg border-2 border-line bg-white sm:w-[140px] w-[120px] flex-shrink-0 shadow-sm">
                                    <button type="button" class="qty-minus flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 active:bg-gray-200 transition-colors cursor-pointer text-lg font-semibold text-black border-0 bg-transparent" aria-label="Decrease quantity">
                                        <span class="leading-none">−</span>
                                    </button>
                                    <div class="quantity body1 font-semibold qty-value px-2 min-w-[2rem] text-center">1</div>
                                    <button type="button" class="qty-plus flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 active:bg-gray-200 transition-colors cursor-pointer text-lg font-semibold text-black border-0 bg-transparent" aria-label="Increase quantity">
                                        <span class="leading-none">+</span>
                                    </button>
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1" class="qty-input">
                                    <button type="submit" class="add-cart-btn button-main whitespace-nowrap w-full text-center bg-white text-black border border-black" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                        Add To Cart
                                    </button>
                                </form>
                            </div>
                            <div class="button-block mt-5 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <a href="{{ route('checkout') }}" class="button-main w-full text-center">Buy It Now</a>
                                <form action="{{ route('enquiries.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="button-main w-full text-center bg-white text-black border border-black">
                                        Enquire Now
                                    </button>
                                </form>
                            </div>
                            <div class="more-infor mt-6 p-5 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center gap-4 flex-wrap">
                                    <div class="flex items-center gap-1">
                                        <i class="ph ph-arrow-clockwise body1"></i>
                                        <div class="text-title">Delivery & Return</div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="ph ph-question body1"></i>
                                        <div class="text-title">Ask A Question</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <i class="ph ph-timer body1"></i>
                                    <div class="text-title">Estimated Delivery:</div>
                                    <div class="text-secondary">{{ now()->addDays(3)->format('d F') }} - {{ now()->addDays(7)->format('d F') }}</div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <i class="ph ph-eye body1"></i>
                                    <div class="text-title">{{ rand(10, 50) }}</div>
                                    <div class="text-secondary">people viewing this product right now!</div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <div class="text-title">SKU:</div>
                                    <div class="text-secondary">{{ $product->id }}{{ str_pad($product->category_id ?? 0, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                                <div class="flex items-center gap-1 mt-3">
                                    <div class="text-title">Categories:</div>
                                    <div class="list-category text-secondary">
                                        @if($product->category)
                                            <a href="{{ route('products.category', $product->category->slug) }}" class="text-secondary hover:underline">{{ $product->category->name }}</a>
                                        @else
                                            Uncategorized
                                        @endif
                                    </div>
                                </div>
                                @if($product->tags->isNotEmpty())
                                    <div class="flex items-center gap-1 mt-3">
                                        <div class="text-title">Tag:</div>
                                        <div class="list-tag text-secondary">
                                            @foreach($product->tags as $tag)
                                                <a href="{{ route('products.tag', $tag->slug) }}" class="text-secondary hover:underline">{{ $tag->name }}</a>@if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="list-payment mt-7">
                                <div class="main-content lg:pt-8 pt-6 lg:pb-6 pb-4 sm:px-4 px-3 border-2 border-gray-200 rounded-xl relative max-md:w-2/3 max-sm:w-full bg-gray-50 shadow-sm">
                                    <div class="heading6 px-5 bg-white absolute -top-[14px] left-1/2 -translate-x-1/2 whitespace-nowrap text-gray-700 font-semibold">Guaranteed safe checkout</div>
                                    <div class="list grid grid-cols-5 gap-3">
                                        <!-- Apple Pay -->
                                        <div class="item flex items-center justify-center lg:px-3 px-2 py-3 rounded-lg bg-white border border-gray-200 hover:border-gray-400 hover:shadow-md transition-all payment-logo-item">
                                            <img src="{{ asset('anvogue/images/payment/apple-pay.png') }}" alt="Apple Pay" class="h-8 w-auto max-w-full object-contain payment-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="payment-logo-fallback hidden items-center justify-center">
                                                <svg class="w-14 h-6" viewBox="0 0 70 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="70" height="24" rx="4" fill="#000"/>
                                                    <path d="M15 9h2.5l-1.5 6h-2.5l1.5-6zm8 0h2l-1 4-1-4zm-4 0h2.5v6h-2.5V9zm6 0h2v6h-2V9z" fill="#fff"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <!-- Tabby -->
                                        <div class="item flex items-center justify-center lg:px-3 px-2 py-3 rounded-lg bg-white border border-gray-200 hover:border-gray-400 hover:shadow-md transition-all payment-logo-item">
                                            <img src="{{ asset('anvogue/images/payment/tabby.png') }}" alt="Tabby" class="h-8 w-auto max-w-full object-contain payment-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="payment-logo-fallback hidden items-center justify-center">
                                                <span class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-3 py-1.5 rounded font-bold text-xs">Tabby</span>
                                            </div>
                                        </div>
                                        <!-- PayPal -->
                                        <div class="item flex items-center justify-center lg:px-3 px-2 py-3 rounded-lg bg-white border border-gray-200 hover:border-gray-400 hover:shadow-md transition-all payment-logo-item">
                                            <img src="{{ asset('anvogue/images/payment/paypal.png') }}" alt="PayPal" class="h-8 w-auto max-w-full object-contain payment-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="payment-logo-fallback hidden items-center justify-center">
                                                <span class="text-blue-600 font-bold text-sm" style="font-family: Arial, sans-serif;">PayPal</span>
                                            </div>
                                        </div>
                                        <!-- Credit Card -->
                                        <div class="item flex items-center justify-center lg:px-3 px-2 py-3 rounded-lg bg-white border border-gray-200 hover:border-gray-400 hover:shadow-md transition-all payment-logo-item">
                                            <img src="{{ asset('anvogue/images/payment/credit-card.png') }}" alt="Credit Card" class="h-8 w-auto max-w-full object-contain payment-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="payment-logo-fallback hidden items-center justify-center">
                                                <svg class="w-12 h-7" viewBox="0 0 48 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="48" height="28" rx="4" fill="#1a1a1a"/>
                                                    <circle cx="14" cy="14" r="3.5" fill="#fff"/>
                                                    <rect x="22" y="10" width="18" height="8" rx="1.5" fill="#fff"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <!-- Debit Card -->
                                        <div class="item flex items-center justify-center lg:px-3 px-2 py-3 rounded-lg bg-white border border-gray-200 hover:border-gray-400 hover:shadow-md transition-all payment-logo-item">
                                            <img src="{{ asset('anvogue/images/payment/debit-card.png') }}" alt="Debit Card" class="h-8 w-auto max-w-full object-contain payment-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="payment-logo-fallback hidden items-center justify-center">
                                                <svg class="w-12 h-7" viewBox="0 0 48 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="48" height="28" rx="4" fill="#2563eb"/>
                                                    <rect x="6" y="9" width="36" height="10" rx="2" fill="#fff"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="desc-tab">
            <div class="container">
                <div class="flex items-center justify-center w-full">
                    <div class="menu-tab flex items-center md:gap-[60px] gap-8">
                        <div class="tab-item heading5 has-line-before text-secondary2 hover:text-black duration-300 active cursor-pointer" data-tab="description">Description</div>
                        <div class="tab-item heading5 has-line-before text-secondary2 hover:text-black duration-300 cursor-pointer" data-tab="specifications">Specifications</div>
                        <div class="tab-item heading5 has-line-before text-secondary2 hover:text-black duration-300 cursor-pointer" data-tab="review">Review</div>
                    </div>
                </div>
                <div class="desc-block mt-8">
                    <div class="desc-item description open" data-item="description">
                        <div class="description-card premium-card">
                            <div class="grid md:grid-cols-2 gap-8 gap-y-5">
                                <div class="left">
                                    <div class="heading6 mb-4">Description</div>
                                    <div class="text-secondary mt-2">
                                        @if($product->long_description)
                                            {!! nl2br(e($product->long_description)) !!}
                                        @elseif($product->description)
                                            {{ $product->description }}
                                        @else
                                            {{ $product->short_description ?? 'No description available.' }}
                                        @endif
                                    </div>
                                </div>
                            <div class="right">
                                <div class="heading6">About This Product</div>
                                <div class="list-feature">
                                    @if($product->short_description)
                                        <div class="item flex gap-1 text-secondary mt-1">
                                            <i class="ph ph-dot text-2xl"></i>
                                            <p>{{ $product->short_description }}</p>
                                        </div>
                                    @endif
                                    @if($product->category)
                                        <div class="item flex gap-1 text-secondary mt-1">
                                            <i class="ph ph-dot text-2xl"></i>
                                            <p>Category: {{ $product->category->name }}</p>
                                        </div>
                                    @endif
                                    @if($product->brand)
                                        <div class="item flex gap-1 text-secondary mt-1">
                                            <i class="ph ph-dot text-2xl"></i>
                                            <p>Brand: {{ $product->brand->name }}</p>
                                        </div>
                                    @endif
                                    <div class="item flex gap-1 text-secondary mt-1">
                                        <i class="ph ph-dot text-2xl"></i>
                                        <p>High quality materials and craftsmanship</p>
                                    </div>
                                    <div class="item flex gap-1 text-secondary mt-1">
                                        <i class="ph ph-dot text-2xl"></i>
                                        <p>Fast and secure shipping</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid lg:grid-cols-4 grid-cols-2 gap-[30px] md:mt-10 mt-6">
                            <div class="item">
                                <div class="icon-delivery-truck text-4xl"></div>
                                <div class="heading6 mt-4">Shipping Faster</div>
                                <div class="text-secondary mt-2">Fast and reliable shipping to get your order to you quickly.</div>
                            </div>
                            <div class="item">
                                <div class="icon-cotton text-4xl"></div>
                                <div class="heading6 mt-4">Premium Quality</div>
                                <div class="text-secondary mt-2">Made with high-quality materials for lasting durability.</div>
                            </div>
                            <div class="item">
                                <div class="icon-guarantee text-4xl"></div>
                                <div class="heading6 mt-4">Quality Guarantee</div>
                                <div class="text-secondary mt-2">We stand behind the quality of all our products.</div>
                            </div>
                            <div class="item">
                                <div class="icon-leaves-compatible text-4xl"></div>
                                <div class="heading6 mt-4">Easy Returns</div>
                                <div class="text-secondary mt-2">Hassle-free returns within 100 days of purchase.</div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="desc-item specifications hidden" data-item="specifications">
                        <div class="specification-card premium-card">
                            @if($product->specification)
                                <div class="specification-content">
                                    {!! $product->specification !!}
                                </div>
                            @elseif($product->attributes->isNotEmpty())
                                <div class="specification-table">
                                    @foreach($product->attributes as $index => $attribute)
                                        <div class="specification-row {{ $index % 2 === 0 ? 'bg-surface' : '' }}">
                                            <div class="specification-label">{{ $attribute->name }}</div>
                                            <div class="specification-value">
                                                @if($attribute->pivot->custom_value)
                                                    {{ $attribute->pivot->custom_value }}
                                                @elseif($attribute->pivot->attribute_value_id)
                                                    {{ $attribute->values->firstWhere('id', $attribute->pivot->attribute_value_id)?->value ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="specification-table">
                                    <div class="specification-row">
                                        <div class="specification-label">Rating</div>
                                        <div class="specification-value">
                                            <div class="flex items-center gap-1">
                                                <div class="rate flex">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="ph-fill ph-star text-xs {{ $i <= round($product->average_rating) ? 'text-yellow' : 'text-secondary' }}"></i>
                                                    @endfor
                                                </div>
                                                <span>({{ number_format($product->average_rating, 1) }})</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="specification-row bg-surface">
                                        <div class="specification-label">Stock</div>
                                        <div class="specification-value">{{ $product->stock_quantity }} available</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="desc-item review hidden" data-item="review">
                        <div class="review-card premium-card">
                            <div class="top-overview flex max-sm:flex-col items-center justify-between gap-12 gap-y-4">
                            <div class="left flex max-sm:flex-col gap-y-4 items-center justify-between lg:w-1/2 sm:w-2/3 w-full sm:pr-5">
                                <div class="rating flex flex-col items-center">
                                    <div class="text-display">{{ number_format($product->average_rating, 1) }}</div>
                                    <div class="flex flex-col items-center">
                                        <div class="rate flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="ph-fill ph-star text-lg {{ $i <= round($product->average_rating) ? 'text-black' : 'text-secondary' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="text-secondary text-center mt-1">({{ number_format($product->total_reviews, 0) }} {{ Str::plural('Rating', $product->total_reviews) }})</div>
                                    </div>
                                </div>
                                <div class="list-rating w-2/3">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="item flex items-center justify-between gap-1.5 {{ $i < 5 ? 'mt-1' : '' }}">
                                            <div class="flex items-center gap-1">
                                                <div class="caption1">{{ $i }}</div>
                                                <i class="ph-fill ph-star text-sm"></i>
                                            </div>
                                            <div class="progress bg-line relative w-3/4 h-2">
                                                <div class="progress-percent absolute bg-yellow h-full left-0 top-0" style="width: {{ $ratingBreakdown[$i]['percentage'] }}%"></div>
                                            </div>
                                            <div class="caption1">{{ $ratingBreakdown[$i]['percentage'] }}%</div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="right">
                                <a href="#form-review" class="button-main bg-white text-black border border-black whitespace-nowrap">Write Reviews</a>
                            </div>
                        </div>
                        <div class="list-review mt-8">
                            <div class="heading flex items-center justify-between flex-wrap gap-4">
                                <div class="heading4">{{ $reviews->count() }} {{ Str::plural('Comment', $reviews->count()) }}</div>
                            </div>
                            @if($reviews->count() > 0)
                                @foreach($reviews as $review)
                                    <div class="item {{ !$loop->last ? 'mt-6 pb-6 border-b border-line' : 'mt-6' }}">
                                        <div class="heading flex items-center justify-between">
                                            <div class="user-infor flex gap-4">
                                                <div class="avatar">
                                                    <div class="w-[52px] aspect-square rounded-full bg-secondary flex items-center justify-center text-white text-xl font-semibold">
                                                        {{ strtoupper(substr($review->customer_name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="user">
                                                    <div class="flex items-center gap-2">
                                                        <div class="text-title">{{ $review->customer_name }}</div>
                                                        <div class="span text-line">-</div>
                                                        <div class="rate flex">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="ph-fill ph-star text-xs {{ $i <= $review->rating ? 'text-yellow' : 'text-secondary' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <div class="text-secondary2">{{ $review->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($review->title)
                                            <div class="mt-3 font-semibold">{{ $review->title }}</div>
                                        @endif
                                        @if($review->comment)
                                            <div class="mt-3">{{ $review->comment }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="item mt-6">
                                    <p class="text-secondary">No reviews yet. Be the first to share your thoughts.</p>
                                </div>
                            @endif
                        </div>
                        <div id="form-review" class="form-review pt-8">
                            <div class="heading4">Leave A comment</div>
                            <form action="{{ route('products.reviews.store', $product) }}" method="POST" class="grid sm:grid-cols-2 gap-4 gap-y-5 mt-6">
                                @csrf
                                <div class="name">
                                    <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('customer_name') border-red @enderror" 
                                           id="username" name="customer_name" type="text" placeholder="Your Name *" 
                                           value="{{ old('customer_name') }}" required>
                                    @error('customer_name') <div class="text-red text-sm mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="mail">
                                    <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('customer_email') border-red @enderror" 
                                           id="email" name="customer_email" type="email" placeholder="Your Email *" 
                                           value="{{ old('customer_email') }}" required>
                                    @error('customer_email') <div class="text-red text-sm mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-span-full">
                                    <label class="block mb-2">Rating *</label>
                                    <div class="flex gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="flex items-center gap-1 cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" {{ old('rating', 5) == $i ? 'checked' : '' }} required>
                                                <span>{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('rating') <div class="text-red text-sm mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-span-full message">
                                    <input class="border border-line px-4 py-3 w-full rounded-lg @error('title') border-red @enderror" 
                                           name="title" type="text" placeholder="Review Title (optional)" 
                                           value="{{ old('title') }}">
                                    @error('title') <div class="text-red text-sm mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-span-full message">
                                    <textarea class="border border-line px-4 py-3 w-full rounded-lg @error('comment') border-red @enderror" 
                                              id="message" name="comment" rows="3" placeholder="Your message *" required>{{ old('comment') }}</textarea>
                                    @error('comment') <div class="text-red text-sm mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-span-full sm:pt-3">
                                    <button type="submit" class="button-main bg-white text-black border border-black">Submit Reviews</button>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
        <div class="tab-features-block filter-product-block md:py-20 py-10">
            <div class="container">
                <div class="heading3 text-center">Related Products</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:mt-10 mt-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="product-item">
                            <div class="bg-white rounded-2xl overflow-hidden h-full flex flex-col">
                                <a href="{{ route('products.show', $relatedProduct->id) }}" class="aspect-[3/4] overflow-hidden block">
                                    <img src="{{ $relatedProduct->image ? asset($relatedProduct->image) : asset('shopus/assets/images/homepage-one/product-img/product-img-7.webp') }}" 
                                         alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                                </a>
                                <div class="p-4 flex flex-col gap-2">
                                    <a href="{{ route('products.show', $relatedProduct->id) }}" class="text-title font-semibold text-black duration-300 hover:text-green">{{ $relatedProduct->name }}</a>
                                    <div>
                                        <span class="text-title font-semibold">{{ $relatedProduct->formatted_effective_price }}</span>
                                        @if($relatedProduct->has_discount)
                                            <span class="caption1 text-secondary line-through ml-1">
                                                {{ $relatedProduct->formatted_price }}
                                            </span>
                                        @endif
                                    </div>
                                    <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="button-main w-full flex items-center justify-center gap-2 whitespace-nowrap" {{ $relatedProduct->stock_quantity <= 0 ? 'disabled' : '' }}>
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
    @endif

    @push('styles')
    <style>
        .gallery-thumbnails-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.3) transparent;
        }
        
        .gallery-thumbnails-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .gallery-thumbnails-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .gallery-thumbnails-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 3px;
        }
        
        .gallery-thumbnails-scroll::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .gallery-thumbnail {
            transition: all 0.2s ease;
        }
        
        .gallery-thumbnail:hover {
            transform: scale(1.05);
        }
        
        .quantity-block {
            background: linear-gradient(to bottom, #ffffff, #f9fafb);
        }
        
        .product-infor {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .main-image-wrapper {
            transition: all 0.3s ease;
        }
        
        .main-image-wrapper:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .payment-logo-item {
            min-height: 50px;
        }
        
        .payment-logo-img {
            filter: grayscale(20%);
            transition: filter 0.2s ease;
        }
        
        .payment-logo-item:hover .payment-logo-img {
            filter: grayscale(0%);
        }
        
        .payment-logo-fallback {
            min-height: 30px;
        }
        
        /* Premium Card Styling */
        .premium-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            margin-bottom: 24px;
        }
        
        .premium-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-color: #d1d5db;
        }
        
        /* Description Card */
        .description-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        }
        
        .description-card .heading6 {
            color: #111827;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .description-card .text-secondary {
            line-height: 1.75;
            color: #4b5563;
        }
        
        /* Specification Card */
        .specification-card {
            background: #ffffff;
        }
        
        .specification-content {
            color: #374151;
            line-height: 1.8;
            font-size: 15px;
        }
        
        .specification-content h1,
        .specification-content h2,
        .specification-content h3 {
            color: #111827;
            font-weight: 600;
            margin-top: 24px;
            margin-bottom: 12px;
        }
        
        .specification-content h1 {
            font-size: 24px;
        }
        
        .specification-content h2 {
            font-size: 20px;
        }
        
        .specification-content h3 {
            font-size: 18px;
        }
        
        .specification-content p {
            margin-bottom: 12px;
        }
        
        .specification-content ul,
        .specification-content ol {
            margin-left: 24px;
            margin-bottom: 12px;
        }
        
        .specification-content li {
            margin-bottom: 8px;
        }
        
        .specification-table {
            width: 100%;
        }
        
        .specification-row {
            display: flex;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s ease;
        }
        
        .specification-row:last-child {
            border-bottom: none;
        }
        
        .specification-row:hover {
            background-color: #f9fafb;
        }
        
        .specification-label {
            font-weight: 600;
            color: #111827;
            min-width: 200px;
            flex-shrink: 0;
        }
        
        .specification-value {
            color: #4b5563;
            flex: 1;
        }
        
        /* Review Card */
        .review-card {
            background: #ffffff;
        }
        
        .review-card .top-overview {
            padding-bottom: 24px;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 32px;
        }
        
        .review-card .list-review .item {
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            margin-bottom: 16px;
            transition: all 0.2s ease;
        }
        
        .review-card .list-review .item:hover {
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }
        
        .review-card .list-review .item:last-child {
            margin-bottom: 0;
        }
        
        .review-card .form-review {
            padding: 24px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            margin-top: 32px;
        }
        
        .review-card .form-review .heading4 {
            color: #111827;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .premium-card {
                padding: 20px;
                border-radius: 12px;
            }
            
            .specification-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .specification-label {
                min-width: auto;
                width: 100%;
            }
            
            .specification-value {
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .list-img .flex {
                flex-direction: column;
            }
            
            .gallery-thumbnails {
                width: 100% !important;
            }
            
            .gallery-thumbnails-scroll {
                display: flex;
                flex-direction: row;
                overflow-x: auto;
                overflow-y: hidden;
                max-height: none;
                gap: 0.75rem;
                padding-bottom: 0.5rem;
            }
            
            .gallery-thumbnail {
                flex-shrink: 0;
                width: 80px;
                margin-bottom: 0 !important;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Store gallery images for JavaScript access
        const galleryImages = @json($galleryImages->values()->all());
        let currentImageIndex = 0;

        // Function to change main image when clicking thumbnails
        function changeMainImage(index) {
            if (index >= 0 && index < galleryImages.length) {
                const mainImage = document.getElementById('mainProductImage');
                if (mainImage) {
                    mainImage.src = galleryImages[index];
                    currentImageIndex = index;
                    
                    // Update thumbnail borders
                    document.querySelectorAll('.gallery-thumbnail').forEach((thumb, i) => {
                        if (i === index) {
                            thumb.classList.remove('border-transparent', 'hover:border-gray-300');
                            thumb.classList.add('border-black');
                        } else {
                            thumb.classList.remove('border-black');
                            thumb.classList.add('border-transparent', 'hover:border-gray-300');
                        }
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Image popup functionality
            const popupImg = document.querySelector('.popup-img');
            const popupLinks = document.querySelectorAll('.popup-link');
            const closePopupBtn = document.querySelector('.close-popup-btn');
            let popupSwiper = null;

            popupLinks.forEach((link) => {
                link.addEventListener('click', () => {
                    popupImg.style.display = 'flex';
                    document.body.style.overflow = 'hidden';

                    if (!popupSwiper) {
                        popupSwiper = new Swiper('.popup-img', {
                            loop: true,
                            slidesPerView: 1,
                            spaceBetween: 0,
                            navigation: {
                                nextEl: '.popup-img .swiper-button-next',
                                prevEl: '.popup-img .swiper-button-prev',
                            },
                            initialSlide: currentImageIndex,
                        });
                    } else {
                        popupSwiper.slideTo(currentImageIndex, 0);
                    }
                });
            });

            if (closePopupBtn) {
                closePopupBtn.addEventListener('click', () => {
                    popupImg.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }

            // Tab switching
            const tabItems = document.querySelectorAll('.tab-item');
            const descItems = document.querySelectorAll('.desc-item');

            tabItems.forEach(tab => {
                tab.addEventListener('click', () => {
                    const targetTab = tab.dataset.tab;
                    
                    tabItems.forEach(t => {
                        t.classList.remove('active', 'text-black');
                        t.classList.add('text-secondary2');
                    });
                    tab.classList.add('active', 'text-black');
                    tab.classList.remove('text-secondary2');

                    descItems.forEach(item => {
                        if (item.dataset.item === targetTab) {
                            item.classList.remove('hidden');
                            item.classList.add('open');
                        } else {
                            item.classList.add('hidden');
                            item.classList.remove('open');
                        }
                    });
                });
            });

            // Color selection
            const colorItems = document.querySelectorAll('.color-item');
            const colorSelected = document.querySelector('.color-selected');
            
            colorItems.forEach(item => {
                item.addEventListener('click', () => {
                    colorItems.forEach(ci => ci.classList.remove('border-black'));
                    item.classList.add('border-black');
                    if (colorSelected) {
                        colorSelected.textContent = item.dataset.color;
                    }
                });
                item.addEventListener('mouseenter', () => {
                    const tag = item.querySelector('.tag-action');
                    if (tag) tag.classList.remove('opacity-0', 'pointer-events-none');
                });
                item.addEventListener('mouseleave', () => {
                    const tag = item.querySelector('.tag-action');
                    if (tag) tag.classList.add('opacity-0', 'pointer-events-none');
                });
            });

            // Size selection
            const sizeItems = document.querySelectorAll('.size-item');
            const sizeSelected = document.querySelector('.size-selected');
            
            sizeItems.forEach(item => {
                item.addEventListener('click', () => {
                    sizeItems.forEach(si => {
                        si.classList.remove('bg-black', 'text-white', 'border-black');
                        si.classList.add('bg-white', 'border-line');
                    });
                    item.classList.add('bg-black', 'text-white', 'border-black');
                    item.classList.remove('bg-white', 'border-line');
                    if (sizeSelected) {
                        sizeSelected.textContent = item.dataset.size;
                    }
                });
            });

            // Quantity controls
            const qtyMinus = document.querySelector('.qty-minus');
            const qtyPlus = document.querySelector('.qty-plus');
            const qtyValue = document.querySelector('.qty-value');
            const qtyInput = document.querySelector('.qty-input');
            const maxQty = {{ $product->stock_quantity }};

            if (qtyMinus && qtyPlus && qtyValue && qtyInput) {
                qtyMinus.addEventListener('click', () => {
                    let current = parseInt(qtyValue.textContent);
                    if (current > 1) {
                        current--;
                        qtyValue.textContent = current;
                        qtyInput.value = current;
                    }
                });

                qtyPlus.addEventListener('click', () => {
                    let current = parseInt(qtyValue.textContent);
                    if (current < maxQty) {
                        current++;
                        qtyValue.textContent = current;
                        qtyInput.value = current;
                    }
                });
            }

            // Countdown Timer Functionality
            const countdownElement = document.querySelector('.countdown-time[data-end-time]');
            if (countdownElement) {
                const endTime = parseInt(countdownElement.getAttribute('data-end-time')) * 1000; // Convert to milliseconds
                const daysElement = countdownElement.querySelector('.days');
                const hoursElement = countdownElement.querySelector('.hours');
                const minsElement = countdownElement.querySelector('.mins');
                const secsElement = countdownElement.querySelector('.secs');

                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = endTime - now;

                    if (distance < 0) {
                        // Timer has ended
                        daysElement.textContent = '00';
                        hoursElement.textContent = '00';
                        minsElement.textContent = '00';
                        secsElement.textContent = '00';
                        
                        // Hide the countdown block
                        const countdownBlock = countdownElement.closest('.countdown-block');
                        if (countdownBlock) {
                            countdownBlock.style.display = 'none';
                        }
                        return;
                    }

                    // Calculate time units
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Update display
                    daysElement.textContent = String(days).padStart(2, '0');
                    hoursElement.textContent = String(hours).padStart(2, '0');
                    minsElement.textContent = String(minutes).padStart(2, '0');
                    secsElement.textContent = String(seconds).padStart(2, '0');
                }

                // Update immediately
                updateCountdown();

                // Update every second
                setInterval(updateCountdown, 1000);
            }
        });
    </script>
    @endpush
@endsection
