@extends('layouts.app')

@section('title', 'Compare Products')

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Compare']
        ]"
        eyebrow="Side-by-side insights"
        title="Compare up to four products in detail."
        description="Choose items from the catalogue and tap “Add to compare” to review fabrics, fits, and pricing before you decide."
        align="center"
    />

    <section class="page-shell pt-0">
        <div class="container">
            <div class="page-card p-5 text-center">
                <img src="{{ asset('shopus/assets/images/homepage-one/empty-cart.webp') }}" alt="Compare" class="mb-4" style="max-width: 220px;">
                <h3 class="h5 mb-2">No products in comparison yet</h3>
                <p class="text-soft mb-3">Browse the catalogue and tap “Add to compare” to see specifications side-by-side.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4">Start shopping</a>
            </div>
        </div>
    </section>
@endsection

