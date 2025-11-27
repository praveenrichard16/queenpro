@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <!-- Breadcrumb Section -->
    <div id="breadcrumb" class="breadcrumb-block relative w-full">
        <div class="bg-img absolute top-0 left-0 right-0 bottom-0 w-full h-full bg-surface">
            @php
                $breadcrumbImage = \App\Models\Setting::getValue('breadcrumb_background_image', '');
            @endphp
            @if($breadcrumbImage)
                <img src="{{ asset('storage/' . $breadcrumbImage) }}" alt="breadcrumb" class="w-full h-full object-cover" />
            @endif
        </div>
        <div class="overlay absolute top-0 left-0 right-0 bottom-0 bg-black opacity-40"></div>
        <div class="container relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1] py-20">
                <div class="text-content">
                    <div class="heading2 text-center text-white">Shopping Cart</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3 text-white">
                        <a href="{{ route('home') }}" class="hover:text-green transition-colors">Homepage</a>
                        <i class="ph ph-caret-right text-sm"></i>
                        <div class="capitalize">Shopping Cart</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cart-block md:py-20 py-10">
        <div class="container">
            @if(count($cart) > 0)
                <div class="content-main flex justify-between max-xl:flex-col gap-y-8">
                    <div class="xl:w-2/3 xl:pr-3 w-full">
                        <!-- Countdown Timer -->
                        <div class="time countdown-cart bg-green py-3 px-5 flex items-center rounded-lg">
                            <div class="heading5">🔥</div>
                            <div class="caption1 pl-2">
                                Your cart will expire in
                                <span class="min text-red text-button fw-700"><span class="minute">10</span> : <span class="second">00</span></span>
                                <span> minutes! Please checkout now before your items sell out!</span>
                            </div>
                        </div>

                        <!-- Free Shipping Progress Bar -->
                        @php
                            $remainingForFreeShipping = max(0, $freeShippingThreshold - $subtotal);
                            $progressPercentage = min(100, ($subtotal / $freeShippingThreshold) * 100);
                        @endphp
                        @if($remainingForFreeShipping > 0)
                            <div class="heading banner mt-5">
                                <div class="text">
                                    Buy
                                    <span class="text-button"> {{ \App\Services\CurrencyService::format($remainingForFreeShipping) }} </span>
                                    <span>more to get </span>
                                    <span class="text-button">freeship</span>
                                </div>
                                <div class="tow-bar-block mt-4">
                                    <div class="progress-line bg-green h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="heading banner mt-5">
                                <div class="text text-green">
                                    🎉 You've qualified for free shipping!
                                </div>
                                <div class="tow-bar-block mt-4">
                                    <div class="progress-line bg-green h-2 rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Product Table -->
                        <div class="list-product w-full sm:mt-7 mt-5">
                            <div class="w-full">
                                <div class="heading bg-surface bora-4 pt-4 pb-4 rounded-lg">
                                    <div class="flex max-md:hidden">
                                        <div class="w-1/2">
                                            <div class="text-button text-center">Products</div>
                                        </div>
                                        <div class="w-1/12">
                                            <div class="text-button text-center">Price</div>
                                        </div>
                                        <div class="w-1/6">
                                            <div class="text-button text-center">Quantity</div>
                                        </div>
                                        <div class="w-1/6">
                                            <div class="text-button text-center">Total Price</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-product-main w-full mt-3">
                                    @foreach($cart as $item)
                                        <div class="item py-5 flex flex-col md:flex-row items-center justify-between gap-4 border-b border-line last:border-0" data-product-id="{{ $item['id'] }}">
                                            <!-- Product Info -->
                                            <div class="flex items-center gap-4 w-full md:w-1/2">
                                                <div class="bg-img w-[100px] aspect-square flex-shrink-0 rounded-lg overflow-hidden">
                                                    <img src="{{ $item['image'] ?: asset('shopus/assets/images/homepage-one/product-img/product-img-4.webp') }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" />
                                                </div>
                                                <div class="w-full">
                                                    <div class="flex items-center justify-between w-full">
                                                        <div class="name text-button">{{ $item['name'] }}</div>
                                                        <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Remove this item?')" class="md:hidden">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                            <button type="submit" class="remove-cart-btn caption1 font-semibold text-red underline cursor-pointer">Remove</button>
                                                        </form>
                                                    </div>
                                                    <div class="text-title mt-2">{{ \App\Services\CurrencyService::format($item['price']) }}</div>
                                                </div>
                                            </div>

                                            <!-- Price (Desktop) -->
                                            <div class="w-1/12 text-center max-md:hidden">
                                                <div class="text-title">{{ \App\Services\CurrencyService::format($item['price']) }}</div>
                                            </div>

                                            <!-- Quantity -->
                                            <div class="w-1/6 flex justify-center">
                                                <form action="{{ route('cart.update') }}" method="POST" class="quantity-form">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                    <div class="quantity-block md:p-3 max-md:py-1.5 max-md:px-3 flex items-center justify-between rounded-lg border border-line sm:w-[180px] w-[120px] flex-shrink-0">
                                                        <button type="button" class="quantity-decrease ph-bold ph-minus cursor-pointer body1 bg-transparent border-0" data-product-id="{{ $item['id'] }}">-</button>
                                                        <input type="number" name="quantity" class="quantity-input body1 font-semibold text-center border-0 bg-transparent w-full" value="{{ $item['quantity'] }}" min="1" max="{{ $item['max_quantity'] }}" readonly>
                                                        <button type="button" class="quantity-increase ph-bold ph-plus cursor-pointer body1 bg-transparent border-0" data-product-id="{{ $item['id'] }}">+</button>
                                                    </div>
                                            </form>
                                            </div>

                                            <!-- Total Price -->
                                            <div class="w-1/6 flex items-center justify-between md:justify-center">
                                                <div class="text-title font-semibold item-total">{{ \App\Services\CurrencyService::format($item['price'] * $item['quantity']) }}</div>
                                                <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Remove this item?')" class="max-md:hidden">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                    <button type="submit" class="remove-cart-btn caption1 font-semibold text-red underline cursor-pointer ml-4">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Discount Code Input -->
                        <div class="input-block discount-code w-full h-12 sm:mt-7 mt-5">
                            <form id="coupon-form" class="w-full h-full relative" method="POST" action="{{ route('coupons.validate') }}">
                                @csrf
                                <input type="text" name="code" id="coupon-code" placeholder="Add voucher discount" class="w-full h-full bg-surface pl-4 pr-14 rounded-lg border border-line" required />
                                <button type="submit" class="button-main absolute top-1 bottom-1 right-1 px-5 rounded-lg flex items-center justify-center">Apply Code</button>
                            </form>
                            <div id="coupon-message" class="mt-2"></div>
                        </div>

                        <!-- Available Vouchers -->
                        @if($availableCoupons->count() > 0)
                            <div class="list-voucher flex items-center gap-5 flex-wrap sm:mt-7 mt-5">
                                @foreach($availableCoupons as $coupon)
                                    <div class="item border border-line rounded-lg py-2">
                                        <div class="top flex gap-10 justify-between px-3 pb-2 border-b border-dashed border-line">
                                            <div class="left">
                                                <div class="caption1">Discount</div>
                                                <div class="caption1 font-bold">
                                                    @if($coupon->type === 'percentage')
                                                        {{ $coupon->value }}% OFF
                                                    @else
                                                        {{ \App\Services\CurrencyService::format($coupon->value) }} OFF
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="right">
                                                <div class="caption1">
                                                    @if($coupon->min_amount)
                                                        For all orders <br />from {{ \App\Services\CurrencyService::format($coupon->min_amount) }}
                                                    @else
                                                        For all orders
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bottom gap-6 items-center flex justify-between px-3 pt-2">
                                            <div class="text-button-uppercase">Code: {{ $coupon->code }}</div>
                                            <button type="button" class="apply-coupon-btn button-main py-1 px-2.5 capitalize text-xs" data-code="{{ $coupon->code }}">Apply Code</button>
                        </div>
                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="xl:w-1/3 xl:pl-12 w-full">
                        <div class="checkout-block bg-surface p-6 rounded-2xl sticky top-6">
                            <div class="heading5">Order Summary</div>
                            
                            <!-- Subtotal -->
                            <div class="total-block py-5 flex justify-between border-b border-line">
                                <div class="text-title">Subtotal</div>
                                <div class="text-title"><span class="total-product">{{ \App\Services\CurrencyService::format($subtotal) }}</span></div>
                            </div>

                            <!-- Discounts -->
                            @if($discountAmount > 0 && $appliedCoupon)
                                <div class="discount-block py-5 flex justify-between border-b border-line">
                                    <div class="text-title">
                                        Discounts
                                        @if($appliedCoupon)
                                            <span class="caption2 text-secondary block">({{ $appliedCoupon['code'] }})</span>
                                        @endif
                                    </div>
                                    <div class="text-title flex items-center gap-2">
                                        <span>-</span><span class="discount">{{ \App\Services\CurrencyService::format($discountAmount) }}</span>
                                        @if($appliedCoupon)
                                            <button type="button" id="remove-coupon-btn" class="text-red caption1 underline ml-2">Remove</button>
                                        @endif
                            </div>
                                </div>
                            @endif

                            <!-- Shipping Options -->
                            <div class="ship-block py-5 flex justify-between border-b border-line">
                                <div class="text-title">Shipping</div>
                                <div class="choose-type flex gap-12 flex-col">
                                    <div class="left">
                                        @if($shippingMethods->count() > 0)
                                            @foreach($shippingMethods as $index => $method)
                                                <div class="type {{ $index > 0 ? 'mt-1' : '' }}">
                                                    <input id="shipping-{{ $method->id }}" type="radio" name="shipping_method" value="{{ $method->id }}" 
                                                           data-cost="{{ $method->cost }}" 
                                                           data-type="{{ $method->type }}"
                                                           {{ $index === 0 ? 'checked' : '' }} />
                                                    <label class="pl-1" for="shipping-{{ $method->id }}">{{ $method->name }}:</label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="type">
                                                <input id="shipping-free" type="radio" name="shipping_method" value="free" checked data-cost="0" data-type="free_shipping" />
                                                <label class="pl-1" for="shipping-free">Free Shipping:</label>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="right">
                                        @if($shippingMethods->count() > 0)
                                            @foreach($shippingMethods as $index => $method)
                                                <div class="shipping-cost {{ $index > 0 ? 'mt-1' : '' }}" data-method-id="{{ $method->id }}">
                                                    @if($method->type === 'free_shipping' || ($method->free_shipping_threshold && $subtotal >= $method->free_shipping_threshold))
                                                        {{ \App\Services\CurrencyService::format(0) }}
                                                    @else
                                                        {{ \App\Services\CurrencyService::format($method->cost) }}
                                                    @endif
                            </div>
                                            @endforeach
                            @else
                                            <div class="shipping-cost" data-method-id="free">{{ \App\Services\CurrencyService::format(0) }}</div>
                                        @endif
                                </div>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="total-cart-block pt-4 pb-4 flex justify-between">
                                <div class="heading5">Total</div>
                                <div class="heading5"><span class="total-cart">{{ \App\Services\CurrencyService::format($total) }}</span></div>
                            </div>

                            <!-- Buttons -->
                            <div class="block-button flex flex-col items-center gap-y-4 mt-5">
                                @auth
                                    <a href="{{ route('checkout') }}" class="checkout-btn button-main text-center w-full">Process To Checkout</a>
                                @else
                                    <a href="{{ route('login', ['redirect' => route('checkout')]) }}" class="checkout-btn button-main text-center w-full">Login To Checkout</a>
                                @endauth
                                <a class="text-button hover-underline" href="{{ route('products.index') }}">Continue shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart State -->
                <div class="bg-white rounded-2xl p-10 text-center">
                    <img src="{{ asset('shopus/assets/images/homepage-one/empty-cart.webp') }}" alt="Empty cart" class="mb-6 mx-auto" style="max-width: 220px;">
                    <h3 class="heading6 mb-3 text-black">Your cart is empty</h3>
                    <p class="caption1 text-secondary mb-5">Browse our latest drops and add items to your bag when you're ready.</p>
                    <a href="{{ route('products.index') }}" class="button-main">Start shopping</a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('anvogue/assets/js/phosphor-icons.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Countdown Timer
            let timeLeft = 600; // 10 minutes in seconds
            const countDownCart = setInterval(function() {
                let minutes = Math.floor(timeLeft / 60);
                if (minutes / 10 < 1) {
                    minutes = `0${minutes}`;
                }

                let seconds = timeLeft % 60;
                if (seconds / 10 < 1) {
                    seconds = `0${seconds}`;
                }

                const minuteTime = document.querySelector(".countdown-cart .minute");
                const secondTime = document.querySelector(".countdown-cart .second");
                
                if (minuteTime && secondTime) {
                    minuteTime.innerHTML = minutes;
                    secondTime.innerHTML = seconds;
                }

                if (timeLeft <= 0) {
                    clearInterval(countDownCart);
                } else {
                    timeLeft--;
                }
            }, 1000);

            // Quantity Controls
            document.querySelectorAll('.quantity-increase').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const input = this.closest('.quantity-block').querySelector('.quantity-input');
                    const max = parseInt(input.getAttribute('max'));
                    const current = parseInt(input.value);
                    if (current < max) {
                        input.value = current + 1;
                        updateCartItem(productId, current + 1);
                    }
                });
            });

            document.querySelectorAll('.quantity-decrease').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const input = this.closest('.quantity-block').querySelector('.quantity-input');
                    const current = parseInt(input.value);
                    if (current > 1) {
                        input.value = current - 1;
                        updateCartItem(productId, current - 1);
                    }
                });
            });

            // Update cart item quantity
            function updateCartItem(productId, quantity) {
                const form = document.querySelector(`.quantity-form input[name="product_id"][value="${productId}"]`).closest('form');
                const formData = new FormData(form);
                formData.set('quantity', quantity);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        // Fallback to form submission
                        form.submit();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Fallback to form submission
                    form.submit();
                });
            }

            // Coupon Form
            const couponForm = document.getElementById('coupon-form');
            if (couponForm) {
                couponForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const code = formData.get('code');
                    const messageDiv = document.getElementById('coupon-message');

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageDiv.innerHTML = '<div class="text-green caption1">' + data.message + '</div>';
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            messageDiv.innerHTML = '<div class="text-red caption1">' + data.message + '</div>';
                        }
                    })
                    .catch(error => {
                        messageDiv.innerHTML = '<div class="text-red caption1">An error occurred. Please try again.</div>';
                    });
                });
            }

            // Apply coupon from voucher list
            document.querySelectorAll('.apply-coupon-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const code = this.getAttribute('data-code');
                    const input = document.getElementById('coupon-code');
                    if (input) {
                        input.value = code;
                        couponForm.dispatchEvent(new Event('submit'));
                    }
                });
            });

            // Remove coupon
            const removeCouponBtn = document.getElementById('remove-coupon-btn');
            if (removeCouponBtn) {
                removeCouponBtn.addEventListener('click', function() {
                    fetch('{{ route("coupons.remove") }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        location.reload();
                    });
                });
            }

            // Shipping method selection
            document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    updateTotal();
                });
            });

            // Update total based on shipping
            function updateTotal() {
                const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
                if (!selectedShipping) return;

                const shippingType = selectedShipping.getAttribute('data-type');
                let shippingCost = parseFloat(selectedShipping.getAttribute('data-cost')) || 0;
                
                // Check if free shipping threshold is met
                const subtotal = {{ $subtotal }};
                const freeShippingThreshold = {{ $freeShippingThreshold }};
                if (shippingType === 'free_shipping' || subtotal >= freeShippingThreshold) {
                    shippingCost = 0;
                }

                const discount = {{ $discountAmount }};
                const tax = {{ $taxAmount }};
                const total = subtotal - discount + tax + shippingCost;

                const totalElement = document.querySelector('.total-cart');
                if (totalElement) {
                    // Use the same format as CurrencyService
                    const formatted = '{{ config("currency.symbol", "$") }}' + total.toFixed(2);
                    totalElement.textContent = formatted;
                }
            }
        });
    </script>
    @endpush
@endsection
