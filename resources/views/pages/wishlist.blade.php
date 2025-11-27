@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Wishlist']
        ]"
        eyebrow="Saved favourites"
        title="Keep tabs on the pieces you love."
        description="Tap the heart icon on any product to add it here. Sign in to sync your shortlist across every device."
        align="center"
    />

    <section class="page-shell pt-0">
        <div class="container">
            <div class="page-card p-5 text-center">
                <img src="{{ asset('shopus/assets/images/homepage-one/empty-wishlist.webp') }}" alt="Empty wishlist" class="mb-4" style="max-width: 220px;">
                <h3 class="h5 mb-2">Your wishlist is feeling lonely</h3>
                <p class="text-soft mb-4">Discover something special and tap the heart to keep it close. We’ll hold it for you and let you know if stock is running low.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary px-4">Shop new arrivals</a>
                    @auth
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-ghost px-4">Go to dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost px-4">Sign in to save</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@endsection

