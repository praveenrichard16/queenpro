@extends('layouts.app')

@section('title', $page->meta_title ?? 'About Us')

@push('meta')
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
@endpush

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'About Us']
        ]"
        eyebrow="About Us"
        :title="$page->title ?? 'About Us'"
        :description="strip_tags($page->content ?? '')"
    />

    <div class="about md:pt-20 pt-10">
        <div class="about-us-block">
            <div class="container">
                @if($page->content)
                    <div class="text flex items-center justify-center">
                        <div class="content md:w-5/6 w-full">
                            <div class="heading3 text-center">{!! $page->content !!}</div>
                        </div>
                    </div>
                @endif
                
                @if($page->image_1 || $page->image_2 || $page->image_3)
                    <div class="list-img grid sm:grid-cols-3 gap-[30px] md:pt-20 pt-10">
                        @if($page->image_1)
                            <div class="bg-img">
                                <img src="{{ asset(ltrim($page->image_1, '/')) }}" alt="About Us Image 1" class="w-full rounded-[30px]" />
                            </div>
                        @endif
                        @if($page->image_2)
                            <div class="bg-img">
                                <img src="{{ asset(ltrim($page->image_2, '/')) }}" alt="About Us Image 2" class="w-full rounded-[30px]" />
                            </div>
                        @endif
                        @if($page->image_3)
                            <div class="bg-img">
                                <img src="{{ asset(ltrim($page->image_3, '/')) }}" alt="About Us Image 3" class="w-full rounded-[30px]" />
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="benefit-block md:pt-20 pt-10">
        <div class="container">
            <div class="list-benefit grid items-start lg:grid-cols-4 grid-cols-2 gap-[30px]">
                <div class="benefit-item flex flex-col items-center justify-center">
                    <i class="icon-phone-call lg:text-7xl text-5xl"></i>
                    <div class="heading6 text-center mt-5">24/7 Customer Service</div>
                    <div class="caption1 text-secondary text-center mt-3">We're here to help you with any questions or concerns you have, 24/7.</div>
                </div>
                <div class="benefit-item flex flex-col items-center justify-center">
                    <i class="icon-return lg:text-7xl text-5xl"></i>
                    <div class="heading6 text-center mt-5">14-Day Money Back</div>
                    <div class="caption1 text-secondary text-center mt-3">If you're not satisfied with your purchase, simply return it within 14 days for a refund.</div>
                </div>
                <div class="benefit-item flex flex-col items-center justify-center">
                    <i class="icon-guarantee lg:text-7xl text-5xl"></i>
                    <div class="heading6 text-center mt-5">Our Guarantee</div>
                    <div class="caption1 text-secondary text-center mt-3">We stand behind our products and services and guarantee your satisfaction.</div>
                </div>
                <div class="benefit-item flex flex-col items-center justify-center">
                    <i class="icon-delivery-truck lg:text-7xl text-5xl"></i>
                    <div class="heading6 text-center mt-5">Shipping worldwide</div>
                    <div class="caption1 text-secondary text-center mt-3">We ship our products worldwide, making them accessible to customers everywhere.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
