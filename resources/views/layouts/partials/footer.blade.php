@php
    $footerCategories = \App\Models\Category::query()
        ->active()
        ->orderBy('name')
        ->limit(6)
        ->get();

    $siteName = $appSettings['site_name'] ?? config('app.name');
    $contactEmail = $appSettings['contact_email'] ?? '';
    $contactPhone = $appSettings['contact_phone'] ?? '';
    $contactAddress = trim(($appSettings['contact_street'] ?? '') . ' ' . ($appSettings['contact_city'] ?? '') . ' ' . ($appSettings['contact_state'] ?? '') . ' ' . ($appSettings['contact_postal'] ?? '') . ' ' . ($appSettings['contact_country'] ?? ''));
    
    $affiliateEnabled = \App\Models\Setting::getValue('affiliate_enabled', '1') !== '0';
@endphp

<div id="footer" class="footer" style="background-color: var(--header-background, #02454f); background-image: none;">
    <div class="footer-main" style="background-color: transparent;">
        <div class="container">
            <div class="content-footer md:py-[60px] py-10 flex justify-between flex-wrap gap-y-8" style="color: #ffffff;">
                <div class="company-infor basis-1/4 max-lg:basis-full pr-7">
                    <a href="{{ route('home') }}" class="logo inline-block">
                        <div class="heading3 w-fit text-white">{{ $siteName }}</div>
                    </a>
                    @if($contactEmail || $contactPhone || $contactAddress)
                        <div class="flex gap-3 mt-3">
                            <div class="flex flex-col">
                                @if($contactEmail)
                                    <span class="text-button text-white">Mail:</span>
                                @endif
                                @if($contactPhone)
                                    <span class="text-button mt-3 text-white">Phone:</span>
                                @endif
                                @if($contactAddress)
                                    <span class="text-button mt-3 text-white">Address:</span>
                                @endif
                            </div>
                            <div class="flex flex-col">
                                @if($contactEmail)
                                    <span class="text-white">{{ $contactEmail }}</span>
                                @endif
                                @if($contactPhone)
                                    <span class="mt-[14px] text-white">{{ $contactPhone }}</span>
                                @endif
                                @if($contactAddress)
                                    <span class="mt-3 pt-1 text-white">{{ $contactAddress }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <div class="right-content flex flex-wrap gap-y-8 basis-3/4 max-lg:basis-full">
                    <div class="list-nav flex justify-between basis-2/3 max-md:basis-full gap-4">
                        <div class="item flex flex-col basis-1/3">
                            <div class="text-button-uppercase pb-3 text-white">Information</div>
                            <a class="caption1 has-line-before duration-300 w-fit text-white hover:text-green" href="{{ route('contact') }}">Contact us</a>
                            <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('about') }}">About Us</a>
                            @if($affiliateEnabled)
                                <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('affiliate.index') }}">Affiliate Program</a>
                            @endif
                            @auth
                                <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('customer.dashboard') }}">My Account</a>
                            @else
                                <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('login') }}">My Account</a>
                            @endauth
                            @auth
                                <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('customer.orders.index') }}">Order & Returns</a>
                            @else
                                <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('login') }}">Order & Returns</a>
                            @endauth
                            <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('contact') }}">FAQs</a>
                        </div>
                        <div class="item flex flex-col basis-1/3">
                            <div class="text-button-uppercase pb-3 text-white">Quick Shop</div>
                            @forelse($footerCategories->take(5) as $category)
                                <a class="caption1 has-line-before duration-300 w-fit {{ $loop->first ? '' : 'pt-2' }} text-white hover:text-green" href="{{ route('products.category', $category->slug) }}">{{ $category->name }}</a>
                            @empty
                                <a class="caption1 has-line-before duration-300 w-fit text-white hover:text-green" href="{{ route('products.index') }}">All Products</a>
                            @endforelse
                            <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('blog.index') }}">Blog</a>
                        </div>
                        <div class="item flex flex-col basis-1/3">
                            <div class="text-button-uppercase pb-3 text-white">Customer Services</div>
                            <a class="caption1 has-line-before duration-300 w-fit text-white hover:text-green" href="{{ route('contact') }}">Orders FAQs</a>
                            <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('contact') }}">Shipping</a>
                            <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('privacy') }}">Privacy Policy</a>
                            <a class="caption1 has-line-before duration-300 w-fit pt-2 text-white hover:text-green" href="{{ route('refund') }}">Return & Refund</a>
                        </div>
                    </div>
                    <div class="newsletter basis-1/3 pl-7 max-md:basis-full max-md:pl-0">
                        <div class="text-button-uppercase text-white">Newsletter</div>
                        <div class="caption1 mt-3 text-white">Sign up for our newsletter and get 10% off your first purchase</div>
                        <div class="input-block w-full h-[52px] mt-4">
                            <form class="w-full h-full relative" method="POST" action="#">
                                @csrf
                                <input type="email" name="email" placeholder="Enter your e-mail" class="caption1 w-full h-full pl-4 pr-14 rounded-xl border border-white/30 bg-white/10 text-white placeholder:text-white/70" required />
                                <button type="submit" class="w-[44px] h-[44px] bg-white flex items-center justify-center rounded-xl absolute top-1 right-1 hover:bg-green">
                                    <i class="ph ph-arrow-right text-xl text-black"></i>
                                </button>
                            </form>
                        </div>
                        <div class="list-social flex items-center gap-6 mt-4">
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener">
                                <div class="icon-facebook text-2xl text-white hover:text-green"></div>
                            </a>
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener">
                                <div class="icon-instagram text-2xl text-white hover:text-green"></div>
                            </a>
                            <a href="https://www.twitter.com/" target="_blank" rel="noopener">
                                <div class="icon-twitter text-2xl text-white hover:text-green"></div>
                            </a>
                            <a href="https://www.youtube.com/" target="_blank" rel="noopener">
                                <div class="icon-youtube text-2xl text-white hover:text-green"></div>
                            </a>
                            <a href="https://www.pinterest.com/" target="_blank" rel="noopener">
                                <div class="icon-pinterest text-2xl text-white hover:text-green"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom py-3 flex items-center justify-between gap-5 max-lg:justify-center max-lg:flex-col border-t border-white/20">
                <div class="left flex items-center gap-8">
                    <div class="copyright caption1 text-white">©{{ now()->year }} {{ $siteName }}. All Rights Reserved.</div>
                </div>
                <div class="right flex items-center gap-2">
                    <div class="caption1 text-white">Payment:</div>
                    <div class="payment-img">
                        <img src="{{ asset('shopus/assets/images/homepage-one/payment-img-1.png') }}" alt="payment" class="w-9" />
                    </div>
                    <div class="payment-img">
                        <img src="{{ asset('shopus/assets/images/homepage-one/payment-img-2.png') }}" alt="payment" class="w-9" />
                    </div>
                    <div class="payment-img">
                        <img src="{{ asset('shopus/assets/images/homepage-one/payment-img-3.png') }}" alt="payment" class="w-9" />
                    </div>
                    <div class="payment-img">
                        <img src="{{ asset('shopus/assets/images/homepage-one/payment-img-4.png') }}" alt="payment" class="w-9" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<a class="scroll-to-top-btn" href="#top-nav"><i class="ph-bold ph-caret-up"></i></a>
