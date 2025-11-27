@extends('layouts.app')

@section('title', 'Affiliate Program')

@push('meta')
    <meta name="description" content="Join our affiliate program and earn commissions by referring customers. Get started today!">
@endpush

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Affiliate Program']
        ]"
        eyebrow="Earn with us"
        title="Join Our Affiliate Program"
        description="Earn money by sharing our products with your audience. Get competitive commissions and grow your income."
    />

    @if(!$affiliateEnabled)
        <section class="py-10 md:py-16">
            <div class="container">
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 text-center">
                    <h3 class="heading6 text-black mb-2">Affiliate Program Currently Unavailable</h3>
                    <p class="caption1 text-secondary mb-0">The affiliate program is temporarily disabled. Please check back later.</p>
                </div>
            </div>
        </section>
    @else
        <section class="py-10 md:py-16">
            <div class="container">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7">
                        <div class="bg-white rounded-2xl p-6 md:p-10">
                            <h3 class="heading6 text-black mb-4">Program Overview</h3>
                            
                            @if($programDescription)
                                <div class="caption1 text-secondary mb-6">
                                    {!! nl2br(e($programDescription)) !!}
                                </div>
                            @else
                                <p class="caption1 text-secondary mb-6">
                                    Our affiliate program allows you to earn commissions by promoting our products. 
                                    Share your unique referral link with your audience and earn a percentage of every sale you generate.
                                </p>
                            @endif

                            <h4 class="heading6 text-black mb-4 mt-8">How It Works</h4>
                            <div class="flex flex-col gap-4 mb-6">
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green text-black flex items-center justify-center font-semibold">1</div>
                                    <div>
                                        <h5 class="caption1 font-semibold text-black mb-1">Sign Up</h5>
                                        <p class="caption2 text-secondary mb-0">Create an account and apply to become an affiliate. Approval is quick and easy.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green text-black flex items-center justify-center font-semibold">2</div>
                                    <div>
                                        <h5 class="caption1 font-semibold text-black mb-1">Get Your Link</h5>
                                        <p class="caption2 text-secondary mb-0">Once approved, you'll receive a unique referral link to share with your audience.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green text-black flex items-center justify-center font-semibold">3</div>
                                    <div>
                                        <h5 class="caption1 font-semibold text-black mb-1">Share & Earn</h5>
                                        <p class="caption2 text-secondary mb-0">Share your link on social media, your blog, or website. Earn commissions on every sale.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green text-black flex items-center justify-center font-semibold">4</div>
                                    <div>
                                        <h5 class="caption1 font-semibold text-black mb-1">Get Paid</h5>
                                        <p class="caption2 text-secondary mb-0">Request payouts when you're ready. We process payments regularly.</p>
                                    </div>
                                </div>
                            </div>

                            <h4 class="heading6 text-black mb-4 mt-8">Benefits</h4>
                            <ul class="list-none p-0 mb-6 space-y-3">
                                <li class="flex items-start gap-3">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-green text-xl flex-shrink-0 mt-0.5"></iconify-icon>
                                    <span class="caption1 text-secondary">Competitive commission rate: {{ number_format($defaultCommissionRate, 2) }}%</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-green text-xl flex-shrink-0 mt-0.5"></iconify-icon>
                                    <span class="caption1 text-secondary">Real-time tracking and analytics</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-green text-xl flex-shrink-0 mt-0.5"></iconify-icon>
                                    <span class="caption1 text-secondary">Easy payout requests</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-green text-xl flex-shrink-0 mt-0.5"></iconify-icon>
                                    <span class="caption1 text-secondary">Marketing materials and support</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-green text-xl flex-shrink-0 mt-0.5"></iconify-icon>
                                    <span class="caption1 text-secondary">Cookie tracking for better conversions</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-2xl p-6 md:p-10 sticky top-4">
                            @if($userAffiliate)
                                @if($userAffiliate->status === 'active')
                                    <div class="text-center mb-6">
                                        <iconify-icon icon="solar:check-circle-bold" class="text-green text-5xl mb-3"></iconify-icon>
                                        <h3 class="heading6 text-black mb-2">You're Already an Affiliate!</h3>
                                        <p class="caption1 text-secondary mb-4">Your affiliate account is active and ready to use.</p>
                                        <a href="{{ route('customer.affiliates.index') }}" class="button-main w-full">Go to Dashboard</a>
                                    </div>
                                @elseif($userAffiliate->status === 'pending')
                                    <div class="text-center mb-6">
                                        <iconify-icon icon="solar:clock-circle-bold" class="text-yellow-500 text-5xl mb-3"></iconify-icon>
                                        <h3 class="heading6 text-black mb-2">Application Pending</h3>
                                        <p class="caption1 text-secondary mb-4">Your affiliate application is being reviewed. We'll notify you once it's approved.</p>
                                        <a href="{{ route('customer.affiliates.index') }}" class="button-main w-full">View Status</a>
                                    </div>
                                @else
                                    <div class="text-center mb-6">
                                        <iconify-icon icon="solar:close-circle-bold" class="text-red-500 text-5xl mb-3"></iconify-icon>
                                        <h3 class="heading6 text-black mb-2">Account Suspended</h3>
                                        <p class="caption1 text-secondary mb-4">Your affiliate account has been suspended. Please contact support for more information.</p>
                                        <a href="{{ route('contact') }}" class="button-main w-full">Contact Support</a>
                                    </div>
                                @endif
                            @else
                                <h3 class="heading6 text-black mb-4">Ready to Get Started?</h3>
                                <p class="caption1 text-secondary mb-6">
                                    @auth
                                        Apply now to become an affiliate and start earning commissions.
                                    @else
                                        Sign in to your account or create a new one to apply for our affiliate program.
                                    @endauth
                                </p>
                                
                                @auth
                                    <form method="POST" action="{{ route('customer.affiliates.apply') }}" class="mb-4">
                                        @csrf
                                        <button type="submit" class="button-main w-full">Apply Now</button>
                                    </form>
                                @else
                                    <div class="flex flex-col gap-3">
                                        <a href="{{ route('login', ['redirect' => route('affiliate.index')]) }}" class="button-main w-full text-center">Sign In</a>
                                        <a href="{{ route('register', ['redirect' => route('affiliate.index')]) }}" class="button-secondary w-full text-center">Create Account</a>
                                    </div>
                                @endauth

                                <div class="mt-6 pt-6 border-t border-line">
                                    <p class="caption2 text-secondary mb-0">
                                        By applying, you agree to our 
                                        <a href="{{ route('terms') }}" class="text-black underline">Terms & Conditions</a> 
                                        and 
                                        <a href="{{ route('privacy') }}" class="text-black underline">Privacy Policy</a>.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

